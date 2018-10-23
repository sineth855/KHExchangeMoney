<?php

namespace App\Http\Models\MoneyTransfer\Information;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Information extends Model
{
    protected $table='information';
  protected $primaryKey='information_id';
  protected $fillable=[
  	'bottom',
    'sort_order',
    'status'
  ];
  public $timestamps=false;

  static function getInformations($id,$lang)
  {
      $getInformation=DB::table('information as inf')
                ->Join('information_description as desc','inf.information_id','=','desc.information_id')
                ->Join('language','language.language_id','=','desc.language_id')
                ->Where('language.language_id',$lang)
                ->select(
                  'inf.information_id',
                  'desc.title',
                  'language.name as language',
                  'inf.sort_order',
                  'inf.bottom',
                  'inf.status',
                  'desc.description',
                  'desc.meta_title',
                  'desc.meta_description',
                  'desc.meta_keyword')
                ->first();
      return $getInformation;
  }

  static function AllInformations()
  {
      $Filters=DB::table('information as inf')
                ->Join('information_description as desc','inf.information_id','=','desc.information_id')
                ->Join('language','language.language_id','=','desc.language_id')
                ->select(
                	'inf.information_id',
                	'desc.title',
                	'language.name as language',
                	'inf.sort_order',
                	'inf.bottom',
                	'inf.status',
                	'desc.description',
                	'desc.meta_title',
                	'desc.meta_description',
                	'desc.meta_keyword')
                ->get();
      return $Filters;
  }
  static function InformationEdit($id)
  {
      $Filters=DB::table('information as inf')
                ->Join('information_description as desc','inf.information_id','=','desc.information_id')
                ->Join('language','language.language_id','=','desc.language_id')
                ->select(
                	'inf.information_id',
                	'desc.title',
                	'language.name as language',
                	'inf.sort_order',
                	'inf.bottom',
                	'inf.status',
                	'desc.description',
                	'desc.meta_title',
                	'desc.meta_description',
                	'desc.meta_keyword')
                ->where('inf.information_id',$id)
                ->get();
      return $Filters;
  }
}
