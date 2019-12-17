<?php

namespace Mailjet\loop;

use Mailjet\Model\MailjetContactList;
use Mailjet\Model\MailjetContactListQuery;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;


class MailjetContactListLoop extends BaseLoop implements PropelSearchLoopInterface
{

    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createAlphaNumStringTypeArgument('name_contact_list'),
            Argument::createBooleanTypeArgument('is_default')
        );
    }

    public function buildModelCriteria()
    {
        $contactList = MailjetContactListQuery::create();

        if(null !== $nameCL = $this->getNameContactList()){
            $contactList->filterByNameCl($nameCL);
        }

        if(null !== $this->getIsDefault()){
            $contactList->filterByDefaultList(1);
        }

        return $contactList;
    }

    public function parseResults(LoopResult $loopResult)
    {
        /** @var MailjetContactList $contact */
        foreach ($loopResult->getResultDataCollection() as $contact) {

            $loopResultRow = new LoopResultRow($contact);
            $loopResultRow->set('ID_CL', $contact->getIdCl());
            $loopResultRow->set('NAME_CL', $contact->getNameCl());
            $loopResultRow->set("SLUG_CL", $contact->getSlugCl());
            $loopResultRow->set("LOCALE", $contact->getLocale());
            $loopResultRow->set("IS_DEFAULT", $contact->getDefaultList());

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}