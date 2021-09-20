<?php
namespace mailCreator;
use Illuminate\Database\Eloquent\Model;
class Campaigns extends Model{
	protected $table='campaigns';
	protected $fillable = ['id','title','link','color','background','facebook','twitter','linkedin','youtube','instagram','menus','logo','user_id','created_at'];
	public function getCampaignsByUser($user_id,$request){
		if($request->has('buscando'))
		{
			return \DB::table('campaign_user')->
						join('campaigns','campaigns.id','=','campaign_user.campaign_id')
						->where('campaign_user.user_id','=',$user_id)
						->where('title','like',"%$request->buscando%")
						->select('campaigns.*')->paginate(20);
		}
		return \DB::table('campaign_user')->
						join('campaigns','campaigns.id','=','campaign_user.campaign_id')
						->where('campaign_user.user_id','=',$user_id)
						->select('campaigns.*')->paginate(20);
	}
}