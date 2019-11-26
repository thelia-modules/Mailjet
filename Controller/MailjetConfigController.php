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

namespace Mailjet\Controller;

use Mailjet\Form\MailjetConfigurationForm;
use Mailjet\Mailjet;
use Mailjet\Model\MailjetContactListQuery;
use Mailjet\Service\ContactListService;
use mysql_xdevapi\Result;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Model\ConfigQuery;
use Thelia\Tools\URL;

/**
 * Class MailjetConfigController
 * @package Mailjet\Controller
 * @author Benjamin Perche <bperche@openstudio.com>
 */
class MailjetConfigController extends BaseAdminController
{
    public function saveAction()
    {
        $baseForm = new MailjetConfigurationForm($this->getRequest());

        try {
            $form = $this->validateForm($baseForm);
            $data = $form->getData();

            ConfigQuery::write(Mailjet::CONFIG_API_KEY, $data["api_key"]);
            ConfigQuery::write(Mailjet::CONFIG_API_SECRET, $data["api_secret"]);
            ConfigQuery::write(Mailjet::CONFIG_API_WS_ADDRESS, $data["ws_address"]);
            ConfigQuery::write(Mailjet::CONFIG_NEWSLETTER_LIST, $data["newsletter_list"]);
            ConfigQuery::write(Mailjet::CONFIG_THROW_EXCEPTION_ON_ERROR, $data["exception_on_errors"] ? true : false);

            $this->getParserContext()->set("success", true);

            if ("close" === $this->getRequest()->request->get("save_mode")) {
                return new RedirectResponse(URL::getInstance()->absoluteUrl("/admin/modules"));
            }
        } catch (\Exception $e) {
            $this->getParserContext()
                ->setGeneralError($e->getMessage())
                ->addForm($baseForm)
            ;
        }

        return $this->render('module-configure', [ 'module_code' => 'Mailjet' ]);
    }

    public function updateContactListAction(Request $request) {
        $id = $request->attributes->get("id");

        $name = $_POST['mailjet_contact_list_configuration']['add_name_address'];
        $slug = $_POST['mailjet_contact_list_configuration']['add_slug_address'];

        MailjetContactListQuery::create()
            ->findOneByIdCl($id)
            ->setNameCl($name)
            ->setSlugCl($slug)
            ->save();
    }

    public function addContactListAction()
    {
        $form = $this->createForm('mailjet.contact.list');

        try {
            $this->validateForm($form);

            $isNewLocalDefault = 0;
            if ( null === $getLocaleDefault = MailjetContactListQuery::create()
                    ->filterByLocale($form->getForm()->get('locale_address')->getData())
                    ->filterByDefaultList(1)
                    ->findOne()
            ) {
                $isNewLocalDefault = 1;
            } elseif (1 === $form->getForm()->get('default_address')->getData()) {
                $isNewLocalDefault = 1;
                $getLocaleDefault->setDefaultList(0)->save();
            }



            $data = [
                'add_name_address' => $form->getForm()->get('add_name_address')->getData(),
                'add_slug_address' => $form->getForm()->get('add_slug_address')->getData(),
                'locale_address' => $form->getForm()->get('locale_address')->getData(),
                'default_address' => $isNewLocalDefault,
            ];

            /** @var ContactListService $contactListService */
            $contactListService = $this->container->get('contact.list');

            $contactListService->addContactList($data);

        } catch (\Exception $e) {
            $this->getParserContext()
                ->setGeneralError($e->getMessage())
                ->addForm($form)
            ;
        }

        return $this->render('module-configure', [ 'module_code' => 'Mailjet' ]);
    }

    public function deleteContactAction(Request $request)
    {
        $idCl = $request->query->get('id_cl');

        /** @var ContactListService $contactListService */
        $contactListService = $this->container->get('contact.list');

        $contactListService->delContactList($idCl);

        return $this->generateRedirect(
            URL::getInstance()->absoluteUrl('/admin/module/Mailjet')
        );
    }

    public function setDefaultContactAction(Request $request)
    {
        $idCl = $request->query->get('id_cl');
        $locale = MailjetContactListQuery::create()->findOneByIdCl($idCl)->getLocale();

        /** @var ContactListService $contactListService */
        $contactListService = $this->container->get('contact.list');

        $contactListService->setDefault($idCl, $locale);

        return $this->generateRedirect(
            URL::getInstance()->absoluteUrl('/admin/module/Mailjet')
        );
    }
}
