<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-sm-8">
        <b-alert v-if="flash.success" variant="success" show>{{ flash.success }}</b-alert>
        <b-alert v-if="flash.error" variant="danger" show>{{ flash.error }}</b-alert>
        <b-card>
          <div slot="header">
            <strong>{{name}}</strong> <small>Form</small>
          </div>
          <form id="form" @submit.prevent="onSubmit">
            <div class="row">
              <div class="col-sm-6">
                <b-form-group id="exampleInputGroup3"
                              label="Master Account *"
                              label-for="exampleInput3">
                  <b-form-select id="exampleInput3"
                                :options="accountMaster"
                                required
                                v-model="account_master">
                  </b-form-select>
                </b-form-group>
                <input type="hidden" required v-model="formData.account_master_id"/>
              </div>
              <div class="col-sm-6">
                <b-form-group id="exampleInputGroup3"
                              label="Account *"
                              label-for="exampleInput3">
                  <b-form-select id="exampleInput3"
                                :options="account"
                                required
                                v-model="formData.account_id">
                  </b-form-select>
                </b-form-group>
              </div>
            </div>
            <input type="hidden" v-model="formData.booking_type"/>
            
            <div class="row">
              <div class="col-sm-6">
                <b-form-group id="exampleInputGroup3"
                              label="Category *"
                              label-for="exampleInput3">
                  <b-form-select id="exampleInput3"
                                :options="category"
                                required
                                v-model="category_id">
                  </b-form-select>
                </b-form-group>
              </div>
              <div class="col-sm-6">
                <b-form-group id="exampleInputGroup3"
                              label="Product *"
                              label-for="exampleInput3">
                  <b-form-select id="exampleInput3"
                                :options="product"
                                required
                                v-model="formData.product_id">
                  </b-form-select>
                </b-form-group>
              </div>
            </div>

            <!--row-->
            <div class="row">
              <div class="col-sm-6">
                <b-form-group id="exampleInputGroup3"
                              label="Booking Type *"
                              label-for="exampleInput3">
                  <b-form-select id="exampleInput3"
                                :options="bookingType"
                                required
                                v-model="booking_type">
                  </b-form-select>
                </b-form-group>
              </div>

              <div class="col-sm-6">
                <b-form-fieldset label="Quantity *">
                  <b-form-input type="number" required v-model="formData.quantity" placeholder="Quantity"></b-form-input>
                </b-form-fieldset>
              </div>
            </div><!--#End Row-->
            <!--row-->
            <div class="row">
              <div class="col-sm-6">
                <b-form-group id="exampleInputGroup3"
                              label="Origin Country"
                              label-for="exampleInput3">
                  <b-form-select id="exampleInput3"
                                :options="country"
                                v-model="country_origin_id">
                  </b-form-select>
                </b-form-group>
              </div>

              <div class="col-sm-6">
                <b-form-group id="exampleInputGroup3"
                              label="Fly Origin *"
                              label-for="exampleInput3">
                  <b-form-select id="exampleInput3"
                                :options="flyOrigin"
                                required
                                v-model="formData.origin">
                  </b-form-select>
                </b-form-group>
              </div>
            </div><!--#End Row-->
            <!--row-->
            <div class="row">
              <div class="col-sm-6">
                <b-form-group id="exampleInputGroup3"
                              label="Destination Country"
                              label-for="exampleInput3">
                  <b-form-select id="exampleInput3"
                                :options="country"
                                v-model="country_destination_id">
                  </b-form-select>
                </b-form-group>
              </div>

              <div class="col-sm-6">
                <b-form-group id="exampleInputGroup3"
                              label="Fly Destination *"
                              label-for="exampleInput3">
                  <b-form-select id="exampleInput3"
                                :options="flyDestination"
                                required
                                v-model="formData.destination">
                  </b-form-select>
                </b-form-group>
              </div>
            </div><!--#End Row-->

            <!--row-->
            <div class="row">
              <div class="col-sm-6">
                <b-form-fieldset label="Fly Date *">
                  <b-form-input type="date" required v-model="formData.fly_date" placeholder="Fly Date"></b-form-input>
                </b-form-fieldset>
              </div>
              <div class="col-sm-6">
                <b-form-fieldset label="Fly Time *">
                  <b-form-input type="time" required v-model="formData.fly_time" placeholder="Fly Time"></b-form-input>
                </b-form-fieldset>
              </div>
            </div><!--#End Row-->
            <!--row-->
            <div class="row"> 
              <div class="col-sm-6">
                <b-form-fieldset v-if="has_booking_type" label="Return Date *">
                  <b-form-input type="date" required v-model="formData.return_date" placeholder="Return Date"></b-form-input>
                </b-form-fieldset>
              </div>
              <div class="col-sm-6">
                <b-form-fieldset v-if="has_booking_type" label="Return Time *">
                  <b-form-input type="time" required v-model="formData.return_time" placeholder="Return Time"></b-form-input>
                </b-form-fieldset>
              </div>
            </div><!--#End Row-->

            <!--row-->
            <!--<div class="row">
              <div class="col-sm-6">
                  <b-form-fieldset>
                      <b-form-checkbox v-model="formData.is_approved">Is Approved?</b-form-checkbox>
                  </b-form-fieldset>
              </div>
            </div>-->
            
            <div _slot="footer">
              <b-button type="reset" @click="onCancel()" size="sm" variant="default"><i class="fa fa-ban"></i> Cancel</b-button>
              <b-button type="submit" v-if="IsStatusApproved" size="sm" variant="primary"><i class="fa fa-dot-circle-o"></i> Submit</b-button>
            </div>
          </form>
        </b-card>
      </div><!--/.col-->
    </div><!--/.row-->
  </div>
</template>

<script>
  import {fetchCategoryList} from '../../../../../api/catalog/category'
  import {fetchProductBaseCategory} from '../../../../../api/catalog/product'
  import {fetchCountryList} from '../../../../../api/setupmaster/country'
  import {fetchList,fetchAccount} from '../../../../../api/merchantAccount/merchantAccount'
  import {createData,editData,updateData} from '../../../../../api/booking/bookings'
  import {fetchFlyLocationBaseCountry,fetchAllFlyLocation} from '../../../../../api/setupmaster/flyLocation'
  import Flash from '../../../../../services/flash'
  export default {
    props:['id'],
    data(){
      return {
        backUrl:'/bookings',
        name:'Bookings Form',
        km2:'',
        booking_type:null,
        account_master:null,
        country_origin_id:null,
        country_destination_id:null,
        account_id:0,
        account_master_id:0,
        category_id:null,
        formData:{
          booking_type:'',
          account_id:null,
          category_id:null,
          product_id:null,
          account_master_id:null,
          origin:null,
          destination:null,
          quantity:0,
          sector:'none',
          booking_date:'',
          fly_date:'',
          fly_time:'',
          return_date:'',
          return_time:'',
          is_approved:false
        },
        IsStatusApproved:true,
        flash: Flash.state,
        has_booking_type:false,
        country: [
          {text:'Select Country', value: null }
        ],
        accountMaster: [
          {text:'Select Master Account', value: null}
        ],
        account_masterForm: [
          {text:'Select Master Account 12', value: null}
        ],
        account: [
          {text:'Select Account', value: null}
        ],
        bookingType: [
          {text:'Select Booking Type', value: null },
          {text:'One Way',value:'One Way'}, 
          {text:'Round Trip',value:'Round Trip'}
        ],
        flyOrigin: [
          {text:'Select Fly Origin', value: null }
        ],
        flyDestination: [
          {text:'Select Fly Destination', value: null }
        ],
        category: [
          {text:'Choose Category', value: null }
        ],
        product: [
          {text:'Select Product', value: null }
        ],
      }
    },
    watch: {
      category_id: function(category_id){
        Flash.setLoading(true)
        this.product = [
            {text:'--Choose Product--', value: null }
        ]
        this.formData.category_id = category_id
        this.getProductBaseCategory(category_id)
      },
      country_origin_id: function(country_id){
        Flash.setLoading(true)
        this.flyOrigin = [
            {text:'Select Fly Origin', value: null }
        ]
        this.getFlyLocationBaseCountryOrigin(country_id)
      },
      country_destination_id: function(country_id){
        Flash.setLoading(true)
        this.flyDestination = [
            {text:'Select Fly Destination', value: null }
        ]
        this.getFlyLocationBaseCountryDestination(country_id)
      },
      booking_type: function(val){
        if(val=='Round Trip'){
          this.has_booking_type = true
          this.formData.booking_type = val
        }else{
          this.has_booking_type = false
          this.formData.booking_type = val
        }
      },
      account_master: function(account_master_id){        
        Flash.setLoading(true)
        this.account = [
          {text:'Select Account', value: null}
        ]
        this.formData.account_master_id = account_master_id
        this.fetchAccountBaseAccountMaster(account_master_id)        
      }
    },
    created(){
      if(this.id){
        Flash.setLoading(true)
        this.getData()
        this.getAllFlyLocation()
        this.fetchAccountBaseAccountMaster(this.account_master_id)
      }
      this.getAllMerchantAccount()
      this.getAllCountry()
      this.getAllCategories()
		},
    methods: {
      getAllCategories(){
        fetchCategoryList().then(response => {
          // this.datatable = response
          for(let x=0 ; x <= response['data'].length-1 ; x++){
            let data = {'text':response['data'][x]['name'],'value':response['data'][x]['category_id']}
            this.category.push(data)
          }
        })
      },
      getProductBaseCategory(category_id){
        fetchProductBaseCategory(category_id).then(response => {
          // this.datatable = response
          for(let x=0 ; x <= response['data'].length-1 ; x++){
            let data = {'text':response['data'][x]['name'],'value':response['data'][x]['product_id']}
            this.product.push(data)
          }
        })
      },
      getAllFlyLocation(){
        fetchAllFlyLocation().then(response => {
          // this.datatable = response
          for(let x=0 ; x <= response['data'].length-1 ; x++){
            let data = {'text':response['data'][x]['name'],'value':response['data'][x]['name']}
            this.flyOrigin.push(data)
            this.flyDestination.push(data)
          }
        })
      },
      getFlyLocationBaseCountryOrigin(country_id){
        fetchFlyLocationBaseCountry(country_id).then(response => {
          // this.datatable = response
          for(let x=0 ; x <= response['data'].length-1 ; x++){
            let data = {'text':response['data'][x]['name'],'value':response['data'][x]['name']}
            this.flyOrigin.push(data)
          }
          Flash.setLoading(false)
        })
      },
      getFlyLocationBaseCountryDestination(country_id){
        fetchFlyLocationBaseCountry(country_id).then(response => {
          // this.datatable = response
          for(let x=0 ; x <= response['data'].length-1 ; x++){
            let data = {'text':response['data'][x]['name'],'value':response['data'][x]['name']}
            this.flyDestination.push(data)
          }
          Flash.setLoading(false)
        })
      },
      getAllCountry() {
        fetchCountryList().then(response => {
          // this.datatable = response
          for(let x=0 ; x <= response['data'].length-1 ; x++){
            let data = {'text':response['data'][x]['name'],'value':response['data'][x]['country_id']}
            this.country.push(data)
          }
        })
      },
      getAllMerchantAccount(){
        fetchList().then(response => {
          // this.datatable = response
          for(let x=0 ; x <= response['data'].length-1 ; x++){
            let data = {'text':response['data'][x]['merchant_name']+' ('+response['data'][x]['merchant_id']+')','value':response['data'][x]['account_master_id']}
            this.accountMaster.push(data)
          }
        })
      },
      fetchAccountBaseAccountMaster(account_master_id){
        fetchAccount(account_master_id).then(response => {
          // this.datatable = response
          for(let x=0 ; x <= response['data'].length-1 ; x++){
            let data = {'text':response['data'][x]['account_no'] + ' ('+response['data'][x]['currency']+')','value':response['data'][x]['account_id']}
            this.account.push(data)
          }
          Flash.setLoading(false)
        })
      },
      clearForm(){
        this.formData = ''
      },
      getData() {
        editData(this.id).then(response => {
          // fetch all form data
          this.account_id = response['data']['account_id']
          this.account_master_id = response['data']['account_master_id']
          this.account_master = response['data']['account_master_id']
          this.category_id = response['data']['category_id']
          this.formData.product_id = response['data']['product_id']
          this.formData.account_id = response['data']['account_id']
          this.formData.account_master_id = response['data']['account_master_id']
          this.booking_type = response['data']['booking_type']
          this.formData.quantity = response['data']['quantity']
          this.formData.origin = response['data']['origin']
          this.formData.destination = response['data']['destination']
          this.formData.fly_date = response['data']['fly_date']
          this.formData.fly_time = response['data']['fly_time']
          this.formData.return_date = response['data']['return_date']
          this.formData.return_time = response['data']['return_time']
          if(response['data']['is_approved']){
            this.formData.is_approved = true
            this.IsStatusApproved = false
          }else{
            this.formData.is_approved = false
            this.IsStatusApproved = true
          }
          this.formData.return_time = response['data']['return_time']
        })
      },
      onCancel(){
        this.$router.push(this.backUrl)
      },
      onSubmit (event) {
        // If has props edit data
        if(this.id){
          updateData(this.formData,this.id).then(response => {
            if(response.success == true){
              Flash.setSuccess(response.message)
            }else{
              Flash.setError(response.message)
            }
          })
        }else{          
          createData(this.formData).then(response => {
            if(response.success == true){
              Flash.setSuccess(response.message)
              this.clearForm()
              event.target.reset()
            }else{
              Flash.setError(response.message)
            }
          })
        }
      }
    }
  }
</script>
