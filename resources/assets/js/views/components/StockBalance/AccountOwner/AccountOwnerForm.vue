<template>
  <form id="form" @submit.prevent="onSubmit">
    <div class="animated fadeIn">
      <b-alert v-if="flash.success" variant="success" show>{{ flash.success }}</b-alert>
      <b-alert v-if="flash.error" variant="danger" show>{{ flash.error }}</b-alert>
      <div class="row">
        <!--col-sm-8-->
        <div class="col-sm-8">
          <b-card>
            <div slot="header">
              <strong>{{name}}</strong> <small>Form</small>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <b-form-group id="exampleInputGroup3"
                              label="Bank Account *"
                              label-for="exampleInput3">
                  <b-form-select id="exampleInput3"
                                :options="bank_account"
                                required
                                v-model="formData.bank_account_id">
                  </b-form-select>
                </b-form-group>
              </div>
              <div class="col-sm-6">
                <b-form-fieldset label="Account Name *">
                  <b-form-input type="text" required id="name" v-model="formData.name" placeholder="Account Name"></b-form-input>
                </b-form-fieldset>
              </div>
            </div>
            
            <div class="row">
              <div class="col-sm-6">
                <b-form-group id="exampleInputGroup3"
                              label="Stock Status *"
                              label-for="exampleInput3">
                  <b-form-select id="exampleInput3"
                                :options="stock_status"
                                required
                                v-model="formData.stock_status_id">
                  </b-form-select>
                </b-form-group>
              </div><!--/.col-->
              <div class="col-sm-6">
                <b-form-group id="exampleInputGroup3"
                              label="Currency *"
                              label-for="exampleInput3">
                  <b-form-select id="exampleInput3"
                                :options="currency"
                                required
                                v-model="formData.currency_id">
                  </b-form-select>
                </b-form-group>
              </div><!--/.col-->
            </div><!--/.row-->

            <div class="row">
              <div class="col-sm-6">
                <b-form-fieldset label="Remark">
                  <b-form-input type="text" id="remark" v-model="formData.remark" placeholder="Remark"></b-form-input>
                </b-form-fieldset>
              </div><!--/.col-->
            </div><!--/.row-->

            
            <div _slot="footer">
              <b-button type="reset" @click="onCancel()" size="sm" variant="default"><i class="fa fa-ban"></i> Cancel</b-button>
              <b-button type="submit" size="sm" variant="primary"><i class="fa fa-dot-circle-o"></i> Submit</b-button>
            </div>
          </b-card>
        </div><!--/.col-->

        <!--col-->
        <!--<div class="col-sm-4">
          <b-card>
            <div slot="header">
              <strong>Photo</strong>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <b-form-input type="file" v-model="formData.image"></b-form-input>
              </div>
            </div>
          </b-card>
        </div>-->
        <!--/.col-->
      </div><!--/.row-->
    </div>
  </form>
</template>

<script>
  import {createData,editData,updateData} from '../../../../../api/stockBalance/accountOwner'
  import {fetchBankAccountList} from '../../../../../api/stockBalance/bankAccount'
  import {fetchStockStatusList} from '../../../../../api/setupmaster/stockStatus'
  import {fetchCurrencyList} from '../../../../../api/setupmaster/currency'

  import Flash from '../../../../../services/flash'
  export default {
    props:['id'],
    data(){
      return {
        backUrl:'/stocks/account-owner',
        name:'Account Owner Form',
        get_country:null,
        formData:{
          bank_account_id:null,
          name:'',
          currency_id:null,
          remark:'',
          stock_status_id:null,
          is_active:true
        },
        flash: Flash.state,
        bank_account:[{text:'Select Bank Account', value: null }],
        currency:[{text:'Choose Currency', value: null }],
        stock_status:[{text:'Choose Stock Status', value: null }]
      }
    },
    watch: {
      get_country: function(val){
        this.getCountry(val)
      }
    },
    created(){
      this.getAllCurrency()
      this.getAllBankAccountList()
      this.getAllStockStatus()
      if(this.id){
        Flash.setLoading(true)
			  this.getData()
      }
		},
    methods: {
      clearForm(){
        this.formData.merchant_name= ""
      },
      getAllBankAccountList() {
        fetchBankAccountList().then(response => {
          // this.datatable = response
          for(let x=0 ; x <= response['data'].length-1 ; x++){
            let data = {'text':response['data'][x]['bank_name'],'value':response['data'][x]['bank_account_id']}
            this.bank_account.push(data)
          }
        })
      },
      getAllStockStatus() {
        fetchStockStatusList().then(response => {
          // this.datatable = response
          for(let x=0 ; x <= response['data'].length-1 ; x++){
            let data = {'text':response['data'][x]['name'],'value':response['data'][x]['stock_status_id']}
            this.stock_status.push(data)
          }
        })
      },
      getAllCurrency() {
        fetchCurrencyList().then(response => {
          // this.datatable = response
          for(let x=0 ; x <= response['data'].length-1 ; x++){
            let data = {'text':response['data'][x]['code'],'value':response['data'][x]['currency_id']}
            this.currency.push(data)
          }
        })
      },
      getData() {
        editData(this.id).then(response => {
          // fetch all form data
          this.formData.bank_account_id = response['data']['bank_account_id']
          this.formData.name = response['data']['name']
          this.formData.currency_id = response['data']['currency_id']
          this.formData.remark = response['data']['remark']
          this.formData.stock_status_id = response['data']['stock_status_id']
          this.formData.is_active = response['data']['is_active']
        })
      },
      onCancel(){
        this.$router.push(this.backUrl)
      },
      onSubmit (event) {
        console.log(this.formData)
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
