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

namespace Mailjet\Form;

use Mailjet\Mailjet;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;
use Thelia\Model\ConfigQuery;

/**
 * Class MailjetConfigurationForm
 * @package Mailjet\Form
 * @author Benjamin Perche <bperche@openstudio.com>
 */
class MailjetConfigurationForm extends BaseForm
{
    /**
     *
     * in this function you add all the fields you need for your Form.
     * Form this you have to call add method on $this->formBuilder attribute :
     *
     * $this->formBuilder->add("name", "text")
     *   ->add("email", "email", array(
     *           "attr" => array(
     *               "class" => "field"
     *           ),
     *           "label" => "email",
     *           "constraints" => array(
     *               new \Symfony\Component\Validator\Constraints\NotBlank()
     *           )
     *       )
     *   )
     *   ->add('age', 'integer');
     *
     * @return null
     */
    protected function buildForm()
    {
        $translator = Translator::getInstance();

        $this->formBuilder
            ->add("api_key", "text", array(
                "label" => $translator->trans("Api key", [], Mailjet::MESSAGE_DOMAIN),
                "label_attr" => ["for" => "api_key"],
                "required" => true,
                "constraints" => array(
                    new NotBlank(),
                ),
                "data" => ConfigQuery::read(Mailjet::CONFIG_API_KEY)
            ))
            ->add("api_secret", "text", array(
                "label" => $translator->trans("Api secret", [], Mailjet::MESSAGE_DOMAIN),
                "label_attr" => ["for" => "api_secret"],
                "required" => true,
                "constraints" => array(
                    new NotBlank(),
                ),
                "data" => ConfigQuery::read(Mailjet::CONFIG_API_SECRET)
            ))
            ->add("newsletter_list", "text", array(
                "label" => $translator->trans("Newsletter list address name", [], Mailjet::MESSAGE_DOMAIN),
                "label_attr" => ["for" => "newsletter_list"],
                "required" => true,
                "constraints" => array(
                    new NotBlank(),
                ),
                "data" => ConfigQuery::read(Mailjet::CONFIG_NEWSLETTER_LIST)
            ))
            ->add("ws_address", "text", array(
                "label" => $translator->trans("Webservice address", [], Mailjet::MESSAGE_DOMAIN),
                "label_attr" => ["for" => "ws_address"],
                "required" => true,
                "constraints" => array(
                    new NotBlank(),
                ),
                "data" => ConfigQuery::read(Mailjet::CONFIG_API_WS_ADDRESS)
            ))
        ;
    }

    /**
     * @return string the name of you form. This name must be unique
     */
    public function getName()
    {
        return "mailjet_configuration";
    }
}