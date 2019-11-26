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
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;
use Thelia\Model\Lang;
use Thelia\Model\LangQuery;

/**
 * Class MailjetConfigurationForm
 * @package Mailjet\Form
 * @author Benjamin Perche <bperche@openstudio.com>
 */
class MailjetContactListConfigurationForm extends BaseForm
{

    protected function buildForm()
    {
        $translator = Translator::getInstance();

        $this->formBuilder
            ->add("add_name_address", "text", array(
                "label" => $translator->trans("Name", [], Mailjet::MESSAGE_DOMAIN),
                "label_attr" => ["for" => "add_name_address"],
                "required" => false,
            ))
            ->add("add_slug_address", "text", array(
                "label" => $translator->trans("Slug", [], Mailjet::MESSAGE_DOMAIN),
                "label_attr" => ["for" => "add_slug_address"],
                "required" => false,
            ))
            ->add("locale_address", "choice", array(
                "label" => $translator->trans("Language", [], Mailjet::MESSAGE_DOMAIN),
                "label_attr" => ["for" => "locale_address"],
                "required" => false,
                "choices" => $this->getLocales(),
            ))
            ->add("default_address", "checkbox", array(
                "label" => $translator->trans("Is default", [], Mailjet::MESSAGE_DOMAIN),
                "label_attr" => ["for" => "default_address"],
                "required" => false,
            ))
        ;
    }

    public function getLocales() {
        $activeLanguages = LangQuery::create()
            ->filterByActive(true)
            ->find();

        $locales = [];

        /** @var Lang $activeLang */
        foreach ($activeLanguages as $activeLang) {
            $locale = $activeLang->getLocale();
            $title = $activeLang->getTitle();
            $locales[$locale] = $title . " (" . $locale . ")";
        }

        return $locales;
    }

    /**
     * @return string the name of you form. This name must be unique
     */
    public function getName()
    {
        return "mailjet_contact_list_configuration";
    }
}
