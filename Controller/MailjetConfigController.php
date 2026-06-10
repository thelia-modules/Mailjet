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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Model\ConfigQuery;
use Thelia\Tools\URL;

/**
 * Class MailjetConfigController
 * @package Mailjet\Controller
 * @author Benjamin Perche <bperche@openstudio.com>
 */
#[Route('/admin/module/Mailjet', name: 'mailjet_config')]
class MailjetConfigController extends BaseAdminController
{
    #[Route('/save', name: '_save', methods: ['POST'])]
    public function saveAction(Request $request): RedirectResponse
    {
        $this->checkAuth(['ADMIN'], ['Mailjet'], 'UPDATE');

        $baseForm = $this->createForm(MailjetConfigurationForm::getName());

        try {
            $form = $this->validateForm($baseForm);
            $data = $form->getData();

            ConfigQuery::write(Mailjet::CONFIG_API_KEY, $data['api_key']);
            ConfigQuery::write(Mailjet::CONFIG_API_SECRET, $data['api_secret']);
            ConfigQuery::write(Mailjet::CONFIG_API_WS_ADDRESS, $data['ws_address']);
            ConfigQuery::write(Mailjet::CONFIG_NEWSLETTER_LIST, $data['newsletter_list']);
            ConfigQuery::write(Mailjet::CONFIG_THROW_EXCEPTION_ON_ERROR, (bool) $data['exception_on_errors']);

            if ('close' === $request->request->get('save_mode')) {
                return new RedirectResponse(URL::getInstance()->absoluteUrl('/admin/modules'));
            }
        } catch (\Exception $e) {
            // Redirect back on error; validation errors are stored in the form
        }

        return $this->generateRedirectFromRoute('admin.module.configure', [], ['module_code' => 'Mailjet']);
    }
}
