<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace Mailjet\EventListeners;

use Mailjet\Model\MailjetNewsletter;
use Mailjet\Model\MailjetNewsletterQuery;
use Mailjet\Api\MailjetClient;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Event\Newsletter\NewsletterEvent;
use Mailjet\Mailjet as MailjetModule;
use Thelia\Log\Tlog;
use Thelia\Model\ConfigQuery;
use Thelia\Model\NewsletterQuery;

/**
 * Class NewsletterListener
 * @package Mailjet\EventListeners
 * @author Benjamin Perche <bperche@openstudio.com>
 */
class NewsletterListener implements EventSubscriberInterface
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var MailjetClient
     */
    protected $api;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;

        // We can't do some beautiful DI because we can't read config variables through the config.xml
        $this->api = new MailjetClient(
            ConfigQuery::read(MailjetModule::CONFIG_API_KEY),
            ConfigQuery::read(MailjetModule::CONFIG_API_SECRET),
            ConfigQuery::read(MailjetModule::CONFIG_API_WS_ADDRESS)
        );
    }

    public function subscribe(NewsletterEvent $event)
    {
        // Create contact
        $model = $this->apiAddUser($event, "registration");

        // Add contact to the thelia list
        $this->apiAddContactList($event, $model);
    }

    public function update(NewsletterEvent $event)
    {
        $previousEmail = NewsletterQuery::create()->findPk($event->getId())->getEmail();

        if ($event->getEmail() !== $previousEmail) {
            $model = MailjetNewsletterQuery::create()->findOneByEmail($previousEmail);

            /**
             * Delete the relation
             */
            $id = $model->getRelationId();

            list ($status, $data) = $this->api->delete(MailjetClient::RESOURCE_LIST_RECIPIENT, $id);

            $this->logAfterAction(
                sprintf("The email address '%s' has been correctly removed from the list", $event->getEmail()),
                sprintf("The email address '%s' has not been removed from the list", $event->getEmail()),
                $status
            );

            /**
             * Then create a new client
             */
            $this->subscribe($event);
        }
    }

    public function unsubscribe(NewsletterEvent $event)
    {
        $model = MailjetNewsletterQuery::create()->findOneByEmail($event->getEmail());

        if (null === $model) {
            return;
        }

        $params = [
            "ContactID" => $model->getId(),
            "ListALT" => ConfigQuery::read(MailjetModule::CONFIG_NEWSLETTER_LIST),
            "IsActive" => "False",
            "IsUnsubscribed" => "True",
        ];

        list ($status, $data) = $this->api->delete(MailjetClient::RESOURCE_LIST_RECIPIENT, $model->getRelationId(), $params);

        $this->logAfterAction(
            sprintf("The email address '%s' has been correctly unsubscribed from the list", $event->getEmail()),
            sprintf("The email address '%s' has not been unsubscribed from the list", $event->getEmail()),
            $status
        );
    }

    protected function apiAddContactList(NewsletterEvent $event, MailjetNewsletter $model)
    {
        $params = [
            "ContactID" => $model->getId(),
            "ListALT" => ConfigQuery::read(MailjetModule::CONFIG_NEWSLETTER_LIST),
            "IsActive" => "True",
            "IsUnsubscribed" => "False",
        ];

        if ($model->isNew()) {
            list ($status, $data) = $this->api->post(MailjetClient::RESOURCE_LIST_RECIPIENT, $params);
        } else {
            list ($status, $data) = $this->api->put(MailjetClient::RESOURCE_LIST_RECIPIENT, $model->getRelationId(), $params);
        }

        $this->logAfterAction(
            sprintf(
                "The following email address has been added into mailjet list.",
                $event->getEmail()
            ),
            sprintf(
                "The following email address has been refused by mailjet for addition in the list.",
                $event->getEmail()
            ),
            $status
        );

        $data = json_decode($data, true);

        $model->setRelationId($data["Data"][0]["ID"])->save();
    }

    protected function apiAddUser(NewsletterEvent $event, $function)
    {
        // Check if the email is already registred
        $model = MailjetNewsletterQuery::create()->findOneByEmail($event->getEmail());

        if (null === $model) {
            list ($status, $data) = $this->api->post(MailjetClient::RESOURCE_CONTACT, [
                "Email" => $event->getEmail(),
                "Name" => $event->getLastname() . " " . $event->getFirstname(),
            ]);

            $this->logAfterAction(
                sprintf("Email address correctly added for %s '%s'", $function, $event->getEmail()),
                sprintf(
                    "The following email address has been refused by mailjet: '%s' for action '%s'",
                    $event->getEmail(),
                    $function
                ),
                $status
            );

            $data = json_decode($data, true);

            $model = new MailjetNewsletter();
            $model
                ->setId($data["Data"][0]["ID"])
                ->setEmail($event->getEmail())
            ;

        }

        return $model;
    }

    protected function logAfterAction($successMessage, $errorMessage, $status)
    {
        if ($status >= 200 && $status < 300) {
            Tlog::getInstance()->info($successMessage);
        } else {
            Tlog::getInstance()->error(sprintf("%s. Status code: %d", $errorMessage, $status));

            throw new \InvalidArgumentException(
                $this->translator->trans(
                    "An error occurred during the newsletter registration process",
                    [],
                    MailjetModule::MESSAGE_DOMAIN
                )
            );
        }
    }

    protected function getEmailFromEvent(NewsletterEvent $event)
    {
        return NewsletterQuery::create()->findPk($event->getId())->getEmail();
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            TheliaEvents::NEWSLETTER_SUBSCRIBE => array("subscribe", 192), // Come before, as if it crashes, it won't be saved by thelia
            TheliaEvents::NEWSLETTER_UPDATE => array("update", 192),
            TheliaEvents::NEWSLETTER_UNSUBSCRIBE => array("unsubscribe", 192),
        );
    }
}
