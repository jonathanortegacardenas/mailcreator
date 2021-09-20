<?php

namespace mailCreator\Http\Controllers;

use Symfony\Component\HttpFoundation\Session\Session;
use mailCreator\Campaigns;
use mailCreator\Blocks;
use mailCreator\User;
use mailCreator\CampaignUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use Illuminate\Contracts\Filesystem\Factory;

class CampaignsController extends Controller {

    public function listCampaigns(Request $request) {
        if (\Auth::user()->type == 0) {
            $campaigns = Campaigns::where('created_at','>=','2019-01-01 00:00:00');
            if($request->has('buscando'))
                $campaigns = $campaigns->where('title','like',"%$request->buscando%");
            $campaigns = $campaigns->orderBy('id', "DESC")->paginate(20);
        } else {
            $camp = new Campaigns;
            $campaigns = $camp->getCampaignsByUser(\Auth::user()->id,$request);
        }
        return view('campaigns/list', array('title' => 'Listado de Campa&ntilde;as', 'campaigns' => $campaigns, 'message' => 'Mira las campa&ntilde;as existentes y administralos', 'class' => 'campaigns', 'type' => \Auth::user()->type));
    }

    public function createCampaign(Request $request) {
        if ($request->has('_token')) {
            try {

                $campaign = $request->all();
                /**
                 * Para array de menus
                 */
                $array_menus = null;
                for ($i = 0; $i < count($campaign['menu_url']); $i++) {
                    $array_menus[$i]['url'] = $campaign['menu_url'][$i];
                    $array_menus[$i]['nombre'] = $campaign['menu_nombres'][$i];
                }
                unset($campaign['menu_url']);
                unset($campaign['menu_nombres']);
                $campaign['menus'] = json_encode($array_menus);
                /**
                 * Para subir la imagen
                 */
                $name='';
                if ($request->has('logo_pred')) {
                    $name = $request->get('logo_pred');
                    unset($campaign['logo_pred']);
                }
                if ($request->hasFile('logo')) {
                    $file = $request->file('logo');
                    $extension = $file->getClientOriginalExtension();
                    $mime = $file->getClientMimeType();
                    $name = date('YmdGis') . '_' . $file->getClientOriginalName();
                    $file->move(public_path() . '/uploads/', $name);
                }
                $campaign['logo'] = $name;
                $campaign['user_id'] = \Auth::user()->id;
                unset($campaign['_token']);
                $newCamp = new Campaigns;
                foreach ($campaign as $row => $value) {
                    $newCamp->$row = $value;
                }
                $newCamp->save();
                $campUs['campaign_id'] = $newCamp->id;
                $campUs['user_id'] = \Auth::user()->id;
                CampaignUser::create($campUs);
                //Campaigns::create($campaign);
                $session = new Session();
                \Session::flash('message', "La campa&ntilde;a se ha ingresado exitosamente");
                \Session::flash('code', '200');
                return Redirect('blocks/list/' . $newCamp->id);
            } catch (Exception $e) {
                \Session::flash('message', "Se ha presentado un error por favor comun&iacute;quese con el administrador");
                \Session::flash('code', "400");
            }
        }
        $campaign = new Campaigns();
        $campaign->title = '';
        $campaign->link = '';
        $campaign->color = '#3865a8';
        $campaign->background = "#FFFFFF";
        $campaign->facebook = "";
        $campaign->twitter = "";
        $campaign->linkedin = '';
        $campaign->youtube = "";
        $campaign->instagram = "";
        $campaign->menus = array(0 => array("url" => "", 'nombre' => ""));
        $campaign->logo = "";
        $campaign->cuenta = "";
        $campaign->envio = 0;
        $campaign->asunto = "";
        $campaign->destino = "";
        return view('campaigns/form', array('title' => 'Crear Campa&ntilde;a', 'action' => url('campaigns/add'), 'message' => 'Crea una nueva campa&ntilde;a en la aplicaci&oacute;n', 'campaign' => $campaign, 'class' => 'campaigns'));
    }

    public function editCampaign($id, Request $request) {
        $campaign = Campaigns::find($id);
        if ($request->has('_token')) {
            try {

                $campaignRequest = $request->all();
                $array_menus = null;
                for ($i = 0; $i < count($campaignRequest['menu_url']); $i++) {
                    $array_menus[$i]['url'] = $campaignRequest['menu_url'][$i];
                    $array_menus[$i]['nombre'] = $campaignRequest['menu_nombres'][$i];
                }
                unset($campaignRequest['menu_url']);
                unset($campaignRequest['menu_nombres']);
                unset($campaignRequest['_token']);
                $campaignRequest['menus'] = json_encode($array_menus);
                if ($request->has('logo_pred')) {
                    $campaignRequest['logo'] = $request->get('logo_pred');
                    unset($campaignRequest['logo_pred']);
                }
                if ($request->hasFile('logo')) {
                    $file = $request->file('logo');
                    $extension = $file->getClientOriginalExtension();
                    $mime = $file->getClientMimeType();
                    $name = date('YmdGis') . '_' . $file->getClientOriginalName();
                    $file->move(public_path() . '/uploads/', $name);
                    if (file_exists(public_path() . '/uploads/' . $campaign->logo)) {
                        unlink(public_path() . '/uploads/' . $campaign->logo);
                    }
                    $campaignRequest['logo'] = $name;
                }
                
                if (isset($campaignRequest['logo']) && $campaignRequest['logo'] == "") {
                    unset($campaignRequest['logo']);
                }
                foreach ($campaignRequest as $row => $value) {
                    $campaign->$row = $value;
                }
                $campaign->save();
                \Session::flash('message', "La campa&ntilde;a se ha editado exitosamente");
                \Session::flash('code', '200');
            } catch (Exception $e) {
                \Session::flash('message', "Se ha presentado un error por favor comun&iacute;quese con el administrador");
                \Session::flash('code', "400");
            }
        }
        $campaign->menus = json_decode($campaign->menus, true);
        return view('campaigns/form', array('title' => 'Editar Campa&ntilde;a: ' . $campaign->title, 'action' => url('campaigns/edit/' . $campaign->id), 'campaign' => $campaign, 'message' => 'Edita una campa&ntilde;a de la aplicaci&oacute;n'));
    }

    public function deleteCampaign($id) {
        $campaign = Campaigns::find($id);
        @unlink(public_path() . '/uploads/' . $campaign->logo);
        $campaign->delete();
        return redirect('campaigns/list');
    }

    public function copyCampaign($id) {
        $campaign = Campaigns::find($id);
        $blocks = Blocks::where('campaign_id', '=', $campaign->id)->get();
        $camp = new Campaigns;
        foreach ($campaign['original'] as $row => $value) {
            if ($row != "id") {
                if ($row == "title") {
                    $camp->$row = $value . ' - Copia ' . date('Y-m-d G:i');
                } elseif ($row == 'created_at') {
                    $camp->$row = date('Y-m-d G:i:s');
                } else {
                    $camp->$row = $value;
                }
            }
        }
        $camp->save();
        $cu['campaign_id'] = $camp->id;
        $cu['user_id'] = \Auth::user()->id;
        CampaignUser::create($cu);
        if (count($blocks) > 0) {
            foreach ($blocks as $b) {
                $block = new Blocks;
                foreach ($b['original'] as $row => $value) {
                    if ($row != 'id')
                        $block->$row = $value;
                }
                $block->campaign_id = $camp->id;
                $block->save();
            }
        }
        return Redirect('campaigns/list/');
    }

    public function downloadCampaign($id) {
        \Session::put('campaign_id', $id);
        $bl = new Blocks;
        $html = $style1 = $style2 = $style3 = $style5 = $style4 = $style6 = $style7 = $style8 = $style9 = "";

        $blocks = Blocks::where('campaign_id', '=', $id)->orderBy('position')->get();
        $campaign = Campaigns::find($id);
        $style1 = $style2 = $style3 = $style5 = $style4 = $style6 = $style7 = $style8 = $style9 = "";
        foreach ($blocks as $b) {
            $html_get = $bl->getTemplate($b->type);
            $htmls = explode('<tr style=" border-top: 1px solid #CECECE;"><td style="text-align:right;"><a href="$$edit$$"><span class="fa fa-pencil" style="font-size:30px">&nbsp;</span></a><a href="$$trash$$"><span class="fa fa-trash-o" style="font-size:30px">&nbsp;</span></a></td></tr>', $html_get);
            if (count($htmls) == 2)
                $html = $htmls[0] . $htmls[1];
            else
                $html = $htmls[0];
            $data = json_decode($b->content, true);
            foreach ($data as $row => $value) {
                if (strstr($row, "image")) {
                    $value = asset("uploads/" . $value);
                }
                if ($row == "name-principal" && $value == '') {
                    $style1 = "display:none;";
                }
                if ($row == "boton_2_1" && ($value == "" || $value == "#")) {
                    $style1 = "display:none;";
                }
                if ($row == "name-secondary" && $value == "") {
                    $style2 = "display:none;";
                }
                if ($row == "name-boton_2" && $value == "") {
                    $style3 = "display:none;";
                }
                if ($row == "link_left" && ($value == "" || $value == "#")) {
                    $style4 = "display:none;";
                }
                if ($row == "link_right" && ($value == "" || $value == "#")) {
                    $style5 = "display:none;";
                }
                if ($row == "link_1" && ($value == "" || $value == "#")) {
                    $style6 = "display:none;";
                }
                if ($row == "link_2" && ($value == "" || $value == "#")) {
                    $style7 = "display:none;";
                }
                if ($row == "link_3" && ($value == "" || $value == "#")) {
                    $style8 = "display:none;";
                }
                if ($row == "name-button" && ($value == "" || $value == "#")) {
                    $style9 = "display:none;";
                }
                $html = str_replace("\$\$" . $row . "\$\$", $value, $html);
            }
            $html = str_replace("\$\$exists_button1\$\$", $style1, $html);
            $html = str_replace("\$\$exists_button2\$\$", $style2, $html);
            $html = str_replace("\$\$exists_button3\$\$", $style3, $html);
            $html = str_replace("\$\$exists_button4\$\$", $style4, $html);
            $html = str_replace("\$\$exists_button5\$\$", $style5, $html);
            $html = str_replace("\$\$exists_button6\$\$", $style6, $html);
            $html = str_replace("\$\$exists_button7\$\$", $style7, $html);
            $html = str_replace("\$\$exists_button8\$\$", $style8, $html);
            $html = str_replace("\$\$exists_button9\$\$", $style9, $html);
            $html = str_replace("\$\$exists_image\$\$", $style9, $html);
            $html = str_replace("\$\$trash\$\$", asset('blocks/delete/' . $b->id), $html);
            $html = str_replace("images/shadow.png", asset('img/shadow.png'), $html);
            $html = str_replace("\$\$shadow\$\$", asset('img/shadow.png'), $html);
            $html = str_replace("\$\$color\$\$", $campaign->color, $html);
            $view[] = $html;
        }
        $data = View('blocks/download', array('title' => $campaign->title, 'campaign' => $campaign, 'blocks' => $view, 'id' => $id));
        $nameFile = date('YmdGis') . 'index.html';
        \Storage::disk('local')->put('htmls/' . $nameFile, $data);
        return response()->download(storage_path() . '/app/htmls/' . $nameFile);
    }

    public function viewCampaign($id) {
        //die("Servicio temporalmente fuera de línea. Comúnicate con nosotros al 3981000. ");
        \Session::put('campaign_id', $id);
        $bl = new Blocks;
        $blocks = Blocks::where('campaign_id', '=', $id)->orderBy('position')->get();
        $campaign = Campaigns::find($id);
        $html = "";
        foreach ($blocks as $b) {
            $html_get = $bl->getTemplate($b->type);
            $htmls = explode('<tr style=" border-top: 1px solid #CECECE;"><td style="text-align:right;"><a href="$$edit$$"><span class="fa fa-pencil" style="font-size:30px">&nbsp;</span></a><a href="$$trash$$"><span class="fa fa-trash-o" style="font-size:30px">&nbsp;</span></a></td></tr>', $html_get);
            if (count($htmls) == 2)
                $html = $htmls[0] . $htmls[1];
            else
                $html = $htmls[0];
            $data = json_decode($b->content, true);

            $style1 = $style2 = $style3 = $style5 = $style4 = $style6 = $style7 = $style8 = $style9 = "";
            foreach ($data as $row => $value) {
                if (strstr($row, "image")) {
                    $value = asset("uploads/" . $value);
                }
                if ($row == "name-principal" && $value == '') {
                    $style1 = "display:none;";
                }
                if ($row == "boton_2_1" && ($value == "" || $value == "#")) {
                    $style1 = "display:none;";
                }
                if ($row == "name-secondary" && $value == "") {
                    $style2 = "display:none;";
                }
                if ($row == "name-boton_2" && $value == "") {
                    $style3 = "display:none;";
                }
                if ($row == "link_left" && ($value == "" || $value == "#")) {
                    $style4 = "display:none;";
                }
                if ($row == "link_right" && ($value == "" || $value == "#")) {
                    $style5 = "display:none;";
                }
                if ($row == "link_1" && ($value == "" || $value == "#")) {
                    $style6 = "display:none;";
                }
                if ($row == "link_2" && ($value == "" || $value == "#")) {
                    $style7 = "display:none;";
                }
                if ($row == "link_3" && ($value == "" || $value == "#")) {
                    $style8 = "display:none;";
                }
                if ($row == "name-button" && ($value == "" || $value == "#")) {
                    $style9 = "display:none;";
                }
                $html = str_replace("\$\$" . $row . "\$\$", $value, $html);
            }
            $html = str_replace("\$\$exists_button1\$\$", $style1, $html);
            $html = str_replace("\$\$exists_button2\$\$", $style2, $html);
            $html = str_replace("\$\$exists_button3\$\$", $style3, $html);
            $html = str_replace("\$\$exists_button4\$\$", $style4, $html);
            $html = str_replace("\$\$exists_button5\$\$", $style5, $html);
            $html = str_replace("\$\$exists_button6\$\$", $style6, $html);
            $html = str_replace("\$\$exists_button7\$\$", $style7, $html);
            $html = str_replace("\$\$exists_button8\$\$", $style8, $html);
            $html = str_replace("\$\$exists_button9\$\$", $style9, $html);
            $html = str_replace("\$\$exists_image\$\$", $style9, $html);
            $html = str_replace("\$\$trash\$\$", asset('blocks/delete/' . $b->id), $html);
            $html = str_replace("images/shadow.png", asset('img/shadow.png'), $html);
            $html = str_replace("\$\$shadow\$\$", asset('img/shadow.png'), $html);
            $html = str_replace("\$\$color\$\$", $campaign->color, $html);
            $view[] = $html;
        }
        return View('blocks/download', array('title' => $campaign->title, 'campaign' => $campaign, 'blocks' => $view, 'id' => $id));
    }

    public function users($id, Request $request) {
        \Session::put('campaign_id', $id);
        if (isset($_POST['user'])) {
            $users = CampaignUser::where('campaign_id', '=', $id)->where('user_id', '=', $request->get('user'))->get();
            if (count($users) == 0) {
                $cu['campaign_id'] = $id;
                $cu['user_id'] = $request->get('user');
                CampaignUser::create($cu);
            }
        }
        $users = User::where('type', '=', '1')->get();
        $campaign_users = new CampaignUser;
        $cus = $campaign_users->getUsersCampaign($id);
        $campaign = Campaigns::find($id);
        return View('campaigns/users', array('campaign_id' => $id, 'title' => 'Asignar Usuarios a la campa&ntilde;a ' . $campaign->name, "message" => "Asiga usuarios a la campaña", 'users' => $users, 'campaign_users' => $cus));
    }

    public function deleteUser($user_id) {
        $users = CampaignUser::where('campaign_id', '=', \Session::get('campaign_id'))->where('user_id', '=', $user_id)->delete();
        return Redirect('campaigns/users/' . \Session::get('campaign_id'));
    }
    public function sendMail($id){
        $mail['html'] = file_get_contents(url('view/'.$id));
        $campaign = Campaigns::find($id);
        \Mail::send('mail', $mail, function($message) use ($campaign) {
                    $message->from($campaign->cuenta);
                    $message->to($campaign->destino)->subject($campaign->asunto);
                    //$message->to("fernando.romero.morales@udla.edu.ec")->subject($campaign->asunto);
        });
        return redirect('campaigns/list');
    }
}
