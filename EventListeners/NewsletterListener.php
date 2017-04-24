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

use Mailjet\Mailjet;
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
        if (null !== $model = $this->apiAddUser($event, "registration")) {
            // Add contact to the thelia list
            $this->apiAddContactList($event, $model);
        }
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

            if ($this->logAfterAction(
                sprintf("The email address '%s' was successfully removed from the list", $event->getEmail()),
                sprintf("The email address '%s' was not removed from the list", $event->getEmail()),
                $status,
                $data
            )) {
                /**
                 * Then create a new client
                 */
                $this->subscribe($event);
            }
        }
    }

    public function unsubscribe(NewsletterEvent $event)
    {
        if (null !== $model = MailjetNewsletterQuery::create()->findOneByEmail($event->getEmail())) {
            // Remove the contact from the contact list. The contact will still exist in Mailjet
            $params = [
                "IsActive" => "False",
                "IsUnsubscribed" => "True",
            ];

            list ($status, $data) = $this->api->put(MailjetClient::RESOURCE_LIST_RECIPIENT, $model->getRelationId(), $params);

            if ($this->logAfterAction(
                sprintf("The email address '%s' was successfully unsubscribed from the list", $event->getEmail()),
                sprintf("The email address '%s' was not unsubscribed from the list", $event->getEmail()),
                $status,
                $data
            )) {
                // Clear internal relation ID
                $model
                    ->setRelationId(0)
                    ->save();
            }
        }
    }

    protected function apiAddContactList(NewsletterEvent $event, MailjetNewsletter $model)
    {
        $params = [
            "ContactID" => $model->getId(),
            "ListALT" => ConfigQuery::read(MailjetModule::CONFIG_NEWSLETTER_LIST),
            "IsActive" => "True",
            "IsUnsubscribed" => "False",
        ];

        // Add the contact to the contact list
        list ($status, $data) = $this->api->post(MailjetClient::RESOURCE_LIST_RECIPIENT, $params);

        if ($this->logAfterAction(
            sprintf(
                "The email address %s was added to mailjet list %s",
                ConfigQuery::read(MailjetModule::CONFIG_NEWSLETTER_LIST),
                $event->getEmail()
            ),
            sprintf(
                "The email address %s was refused by mailjet for addition to the list %s, params:%s",
                $event->getEmail(),
                ConfigQuery::read(MailjetModule::CONFIG_NEWSLETTER_LIST),
                json_encode($params)
            ),
            $status,
            $data
        )) {
            $data = json_decode($data, true);

            // Save the contact/contact-list relation ID, we'll need it for unsubscription.
            $model
                ->setRelationId($data["Data"][0]["ID"])
                ->save()
            ;
        }
    }

    protected function apiAddUser(NewsletterEvent $event, $function)
    {
        // Check if the email is already registred
        $model = MailjetNewsletterQuery::create()->findOneByEmail($event->getEmail());

        if (null === $model) {
            // Check if user exists before trying to create it (fixes sync. problems)
            list ($status, $data) = $this->api->get(MailjetClient::RESOURCE_CONTACT, $event->getEmail());

            if ($status == 404) {
                list ($status, $data) = $this->api->post(MailjetClient::RESOURCE_CONTACT, [
                    "Email" => $event->getEmail(),
                    "Name" => $event->getLastname() . " " . $event->getFirstname(),
                ]);
            }

            if ($this->logAfterAction(
                sprintf("Email address successfully added for %s '%s'", $function, $event->getEmail()),
                sprintf(
                    "The email address %s was refused by mailjet for action '%s'",
                    $event->getEmail(),
                    $function
                ),
                $status,
                $data
            )) {
                $data = json_decode($data, true);

                $model = new MailjetNewsletter();
                $model
                    ->setId($data["Data"][0]["ID"])
                    ->setEmail($event->getEmail())
                    ->save();
            }
        }

        return $model;
    }

    protected function isStatusOk($status)
    {
        return $status >= 200 && $status < 300;
    }

    protected function logAfterAction($successMessage, $errorMessage, $status, $data)
    {
        if ($this->isStatusOk($status)) {
            Tlog::getInstance()->info($successMessage);

            return true;
        } else {
            Tlog::getInstance()->error(sprintf("%s. Status code: %d, data: %s", $errorMessage, $status, $data));

            if (ConfigQuery::read(Mailjet::CONFIG_THROW_EXCEPTION_ON_ERROR, false)) {
                throw new \InvalidArgumentException(
                    $this->translator->trans(
                        "An error occurred during the newsletter registration process",
                        [],
                        MailjetModule::MESSAGE_DOMAIN
                    )
                );
            }

            return false;
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
