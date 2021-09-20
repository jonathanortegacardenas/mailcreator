<?php 
namespace mailCreator;
use Illuminate\Database\Eloquent\Model;
\DB::enableQueryLog();
Class CampaignUser extends Model{
	protected $table='campaign_user';
	protected $fillable = ['id','campaign_id','user_id'];
	public function getUsersCampaign($campaign_id){
		return \DB::table('campaign_user')->join('users','users.id','=','campaign_user.user_id')
            ->where('campaign_user.campaign_id','=',$campaign_id)->get();
	}
}