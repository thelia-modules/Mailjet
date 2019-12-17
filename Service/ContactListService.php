<?php

namespace Mailjet\Service;


use Mailjet\Model\MailjetContactList;
use Mailjet\Model\MailjetContactListQuery;
use Thelia\Core\Translation\Translator;
use Thelia\Form\Exception\FormValidationException;

class ContactListService
{
    public function addContactList($data)
    {

        $contactList = MailjetContactListQuery::create()
            ->filterByNameCl($data['add_name_address'])
            ->filterBySlugCl($data['add_slug_address'])
            ->findOne();

        if (null == $contactList) {

            $contactList = new MailjetContactList();
            $contactList
                ->setNameCl($data['add_name_address'])
                ->setSlugCl($data['add_slug_address'])
                ->setLocale($data['locale_address'])
                ->setDefaultList($data['default_address'])
                ->save();
        } else {
            throw new FormValidationException(Translator::getInstance()->trans("Contact list duplication"));
        }
    }

    public function delContactList($idCl)
    {
        $contactList = MailjetContactListQuery::create()
            ->filterByIdCl($idCl)
            ->findOne();

        if(null != $contactList){
            if ($contactList->getDefaultList() === true) {
                if (null !== $newDefault = MailjetContactListQuery::create()->filterByLocale($contactList->getLocale())->findOne()) {
                    $newDefault->setDefaultList(1)->save();
                }
            }
            $contactList->delete();
        }
    }

    public function setDefault($idCl, $locale)
    {
        /** @var MailjetContactList $contactList */
        $contactList = MailjetContactListQuery::create()
            ->filterByLocale($locale)
            ->filterByDefaultList(1)
            ->findOne();

        if (null !== $contactList) {
            $contactList
                ->setDefaultList(0)
                ->save();
        }

        $contactList = MailjetContactListQuery::create()
            ->filterByIdCl($idCl)
            ->findOne();

        if(null != $contactList){
            $contactList
                ->setDefaultList(1)
                ->save();
        }
    }

    public function getContactlistByName($name)
    {
        /** @var MailjetContactList $contactList */
        $contactList = MailjetContactListQuery::create()
            ->filterByNameCl($name)
            ->findOne();

        if(null != $contactList){
            return $contactList->getSlugCl();
        }
    }
}