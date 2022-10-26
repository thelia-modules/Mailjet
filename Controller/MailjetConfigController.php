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
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Template\ParserContext;
use Thelia\Model\ConfigQuery;
use Thelia\Tools\URL;

/**
 * Class MailjetConfigController
 * @package Mailjet\Controller
 * @author Benjamin Perche <bperche@openstudio.com>
 */
class MailjetConfigController extends BaseAdminController
{
    public function saveAction(Request $request, ParserContext $parserContext)
    {
        $baseForm = $this->createForm(MailjetConfigurationForm::getName());

        try {
            $form = $this->validateForm($baseForm);
            $data = $form->getData();

            ConfigQuery::write(Mailjet::CONFIG_API_KEY, $data["api_key"]);
            ConfigQuery::write(Mailjet::CONFIG_API_SECRET, $data["api_secret"]);
            ConfigQuery::write(Mailjet::CONFIG_API_WS_ADDRESS, $data["ws_address"]);
            ConfigQuery::write(Mailjet::CONFIG_NEWSLETTER_LIST, $data["newsletter_list"]);
            ConfigQuery::write(Mailjet::CONFIG_THROW_EXCEPTION_ON_ERROR, (bool)$data["exception_on_errors"]);

            $parserContext->set("success", true);

            if ("close" === $request->request->get("save_mode")) {
                return new RedirectResponse(URL::getInstance()->absoluteUrl("/admin/modules"));
            }
        } catch (\Exception $e) {
            $parserContext
                ->setGeneralError($e->getMessage())
                ->addForm($baseForm)
            ;
        }

        return $this->render('module-configure', [ 'module_code' => 'Mailjet' ]);
    }
}
