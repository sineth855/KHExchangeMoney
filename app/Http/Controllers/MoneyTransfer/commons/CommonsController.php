<?php

namespace App\Http\Controllers\MoneyTransfer\commons;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
class CommonsController extends Controller
{
    public function getLanguage()
    {

        $languages=DB::table('language')->select(['language_id as value','name as text'])->get();

        return response()->json($languages);

    }
    public function getCreditType()
    {

        $creditType=DB::table('credit_type')->select(['credit_type_id as value','name as text'])->get();

        return response()->json($creditType);

    }
    public function getGeoZone()
    {

        $GeoZone=DB::table('geo_zone')->select(['geo_zone_id as value','name as text'])->get();

        return response()->json($GeoZone);

    }
    public function getTaxRate()
    {

        $TaxRate=DB::table('tax_rate')->select(['tax_rate_id as value','name as text'])->get();

        return response()->json($TaxRate);

    }
    public function getTaxClass()
    {

        $TaxClass=DB::table('tax_class')->select(['tax_class_id as value','title as text'])->get();

        return response()->json($TaxClass);

    }
    public function getCustomerGroup()
    {

        $TaxClass=DB::table('customer_group_description')->select(['customer_group_id as value','name as text'])->get();

        return response()->json($TaxClass);

    }
    
    public function getStore()
    {

        $stores=DB::table('store')->select(['store_id as value','name as text'])->get();

        return response()->json($stores);

    }
    public function getAttributeGroup()
    {

        $atrributeGroup=DB::table('attribute_group_description')->select(['attribute_group_id as value','name as text'])->get();

        return response()->json($atrributeGroup);

    }
    public function getFilterGroup()
    {

        $filterGroup=DB::table('filter_group_description')->select(['filter_group_id as value','name as text'])->get();

        return response()->json($filterGroup);

    }
    public function getLayout()
    {

        $filterGroup=DB::table('layout')->select(['layout_id as value','name as text'])->get();

        return response()->json($filterGroup);

    }
}
