<?php
namespace App\Http\Controllers;
use Symfony\Component\HttpFoundation\Session\Session;
use mailCreator\Blocks;
use mailCreator\Campaigns;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;

class BlocksController extends Controller
{
	public function listBlocks($id){
        Session::put('campaign_id',$id);

		$bl = new Blocks;
		$html = "";
		$blocks = Blocks::where('campaign_id','=',$id)->orderBy('position')->get();
		$campaign = Campaigns::find($id);
		$view = null;
		foreach($blocks as $b){
			$html = $bl->getTemplate($b->type);
			$data = json_decode($b->content,true);
			foreach($data as $row => $value){
				if(strstr($row,"image")){
					$value = asset("uploads/".$value);
				}
				$html = str_replace("$$".$row."$$",$value,$html);
			}
			$html = str_replace("\$\$edit\$\$",asset('blocks/edit/'.$b->id),$html);
			$html = str_replace("\$\$trash\$\$",asset('blocks/delete/'.$b->id),$html);
			$html = str_replace("images/shadow.png",asset('img/shadow.png'),$html);
			$html = str_replace("\$\$shadow\$\$",asset('img/shadow.png'),$html);
            if(isset($campaign->color)){
			$html = str_replace("\$\$color\$\$",$campaign->color,$html);
            }
                        $html = str_replace("\$\$position\$\$",$b->position,$html);
			$view[] = $html;
		}
		return View('blocks/view',array('title'=>"Previsualizaci&oacute;n del Correo - Campa&ntilde;a ".$campaign->title,'message'=>"Previsualiza el correo electrónico que será enviado",'campaign'=>$campaign,'blocks'=>$view));
	}
	public function addBlock(Request $request){

		if($request->has('_token')){
			$blockInfo = $request->all();
			$saveArray = null;
			unset($blockInfo['_token']);
			$saveArray['type']=$blockInfo['type_selected'];
			unset($blockInfo['type_selected']);
			if($request->hasFile('image')){
				$file = $request->file('image');
				$extension = $file->getClientOriginalExtension();
				$mime = $file->getClientMimeType();
				$name = date('YmdGis').'_'.$file->getClientOriginalName();
				$file->move(public_path().'/uploads/',$name);
				$blockInfo['image']=$name;
		 	}
		 	if($request->hasFile('image_right')){
				$file = $request->file('image_right');
				$extension = $file->getClientOriginalExtension();
				$mime = $file->getClientMimeType();
				$name = date('YmdGis').'_right_'.$file->getClientOriginalName();
				$file->move(public_path().'/uploads/',$name);
				$blockInfo['image_right']=$name;
		 	}
		 	if($request->hasFile('image_left')){
				$file = $request->file('image_left');
				$extension = $file->getClientOriginalExtension();
				$mime = $file->getClientMimeType();
				$name = date('YmdGis').'_left_'.$file->getClientOriginalName();
				$file->move(public_path().'/uploads/',$name);
				$blockInfo['image_left']=$name;
		 	}
		 	if($request->hasFile('image_1')){
				$file = $request->file('image_1');
				$extension = $file->getClientOriginalExtension();
				$mime = $file->getClientMimeType();
				$name = date('YmdGis').'_1_'.$file->getClientOriginalName();
				$file->move(public_path().'/uploads/',$name);
				$blockInfo['image_1']=$name;
		 	}
		 	if($request->hasFile('image_2')){
				$file = $request->file('image_2');
				$extension = $file->getClientOriginalExtension();
				$mime = $file->getClientMimeType();
				$name = date('YmdGis').'_2_'.$file->getClientOriginalName();
				$file->move(public_path().'/uploads/',$name);
				$blockInfo['image_2']=$name;
		 	}
		 	if($request->hasFile('image_3')){
				$file = $request->file('image_3');
				$extension = $file->getClientOriginalExtension();
				$mime = $file->getClientMimeType();
				$name = date('YmdGis').'_3_'.$file->getClientOriginalName();
				$file->move(public_path().'/uploads/',$name);
				$blockInfo['image_3']=$name;
		 	}
		 	if($request->hasFile('image_4')){
				$file = $request->file('image_4');
				$extension = $file->getClientOriginalExtension();
				$mime = $file->getClientMimeType();
				$name = date('YmdGis').'_4_'.$file->getClientOriginalName();
				$file->move(public_path().'/uploads/',$name);
				$blockInfo['image_4']=$name;
		 	}
		 	$saveArray['content']=json_encode($blockInfo);
			$saveArray['campaign_id'] = Session::get('campaign_id');
			$blocks = new Blocks();
			$last = $blocks->getMaxPosition($saveArray['campaign_id'] );
			$saveArray['position']=((is_null($last))?0:$last)+1;
			Blocks::create($saveArray);

			return Redirect('blocks/list/' . Session::get('campaign_id'));
		}
		return View('blocks/create',array('title'=>"Agregar Bloque al correo",'message'=>'Puede agregar nuevos bloques para aumentar el contenido del correo electr&oacute;nico desde esta p&aacute;gina'));
	}
	public function editBlock($id,Request $request){
		$bl = new Blocks;
		$block = Blocks::find($id);
		$html_form = $bl->getForm($block->type);
		$campaign = Campaigns::find($block->campaign_id);
		if($_POST){
			$content = json_decode($block->content,true);
			$blockInfo = $request->all();
			unset($blockInfo['_token']);
			unset($blockInfo['type_selected']);
			if($request->hasFile('image')){
				$file = $request->file('image');
				$extension = $file->getClientOriginalExtension();
				$mime = $file->getClientMimeType();
				$name = date('YmdGis').'_'.$file->getClientOriginalName();
				$file->move(public_path().'/uploads/',$name);
				$blockInfo['image']=$name;
		 	}else{
		 		if(isset($content['image']))
		 			$blockInfo['image']=$content['image'];
		 	}
		 	if($request->hasFile('image_right')){
				$file = $request->file('image_right');
				$extension = $file->getClientOriginalExtension();
				$mime = $file->getClientMimeType();
				$name = date('YmdGis').'_right_'.$file->getClientOriginalName();
				$file->move(public_path().'/uploads/',$name);
				$blockInfo['image_right']=$name;
		 	}else{
		 		if(isset($content['image_right']))
		 			$blockInfo['image_right']=$content['image_right'];
		 	}
		 	if($request->hasFile('image_left')){
				$file = $request->file('image_left');
				$extension = $file->getClientOriginalExtension();
				$mime = $file->getClientMimeType();
				$name = date('YmdGis').'_left_'.$file->getClientOriginalName();
				$file->move(public_path().'/uploads/',$name);
				$blockInfo['image_left']=$name;
		 	}else{
		 		if(isset($content['image_left']))
		 			$blockInfo['image_left']=$content['image_left'];
		 	}
		 	if($request->hasFile('image_1')){
				$file = $request->file('image_1');
				$extension = $file->getClientOriginalExtension();
				$mime = $file->getClientMimeType();
				$name = date('YmdGis').'_1_'.$file->getClientOriginalName();
				$file->move(public_path().'/uploads/',$name);
				$blockInfo['image_1']=$name;
		 	}else{
		 		if(isset($content['image_1']))
		 			$blockInfo['image_1']=$content['image_1'];
		 	}
		 	if($request->hasFile('image_2')){
				$file = $request->file('image_2');
				$extension = $file->getClientOriginalExtension();
				$mime = $file->getClientMimeType();
				$name = date('YmdGis').'_2_'.$file->getClientOriginalName();
				$file->move(public_path().'/uploads/',$name);
				$blockInfo['image_2']=$name;
		 	}else{
		 		if(isset($content['image_2']))
		 			$blockInfo['image_2']=$content['image_2'];
		 	}
		 	if($request->hasFile('image_3')){
				$file = $request->file('image_3');
				$extension = $file->getClientOriginalExtension();
				$mime = $file->getClientMimeType();
				$name = date('YmdGis').'_3_'.$file->getClientOriginalName();
				$file->move(public_path().'/uploads/',$name);
				$blockInfo['image_3']=$name;
		 	}else{
		 		if(isset($content['image_3']))
		 			$blockInfo['image_3']=$content['image_3'];
		 	}
		 	if($request->hasFile('image_4')){
				$file = $request->file('image_4');
				$extension = $file->getClientOriginalExtension();
				$mime = $file->getClientMimeType();
				$name = date('YmdGis').'_4_'.$file->getClientOriginalName();
				$file->move(public_path().'/uploads/',$name);
				$blockInfo['image_4']=$name;
		 	}else{
		 		if(isset($content['image_4']))
		 			$blockInfo['image_4']=$content['image_4'];
		 	}
		 	$block->content = json_encode($blockInfo);
            $block->position = $blockInfo['position'];
			$block->save();
			return Redirect('/blocks/list/'.$campaign->id);
		}
		$htmlData = $bl->getTemplate($block->type);
		$block = Blocks::find($id);
		$content = json_decode($block->content,true);
		foreach($content as $row=>$value){
			if(strstr($row,'image')){
				$value = asset('uploads/'.$value);
			}

			$htmlData  = str_replace("\$\$".$row."\$\$",$value, $htmlData);
			$html_form = str_replace("\$\$".$row."\$\$",$value,$html_form);
                        $html_form = str_replace("\$\$position\$\$",$block->position,$html_form);
                        $htmlData = str_replace("\$\$edit\$\$",asset('blocks/edit/'.$bl->id),$htmlData);
			$htmlData = str_replace("\$\$trash\$\$",asset('blocks/delete/'.$bl->id),$htmlData);
			$htmlData = str_replace("images/shadow.png",asset('img/shadow.png'),$htmlData);
			$htmlData = str_replace("\$\$shadow\$\$",asset('img/shadow.png'),$htmlData);
			$htmlData = str_replace("\$\$color\$\$",$campaign->color,$htmlData);

              	}

		return View('blocks/edit',array('title'=>'Editar bloque','message'=>'Realiza edici&oacute;n de un bloque previamente creado','form'=>$html_form,'html_data'=>$htmlData,'id'=>$id));
	}
	public function deleteBlock($id){
		$block = Blocks::find($id);
		$block->delete();
		return Redirect('campaigns/list');
	}
}
