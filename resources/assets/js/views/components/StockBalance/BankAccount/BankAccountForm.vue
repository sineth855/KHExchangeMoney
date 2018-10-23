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
                <b-form-fieldset label="Merchant ID">
                  <b-form-input type="text" v-model="formData.merchant_id" placeholder="Merchant ID"></b-form-input>
                </b-form-fieldset>
              </div>
              <div class="col-sm-6">
                <b-form-fieldset label="Merchant Name">
                  <b-form-input type="text" v-model="formData.merchant_name" placeholder="Merchant Name"></b-form-input>
                </b-form-fieldset>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-6">
                <b-form-fieldset label="Bank Name *">
                  <b-form-input type="text" required v-model="formData.bank_name" placeholder="Bank Name"></b-form-input>
                </b-form-fieldset>
              </div>
              <div class="col-sm-6">
                <b-form-fieldset label="Account No *">
                  <b-form-input type="text" required v-model="formData.account_no" placeholder="Account No"></b-form-input>
                </b-form-fieldset>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-6">
                <b-form-fieldset label="Account Name *">
                  <b-form-input type="text" required v-model="formData.account_name" placeholder="Account Name"></b-form-input>
                </b-form-fieldset>
              </div>
              <div class="col-sm-6">
                <b-form-fieldset label="Expired Account *">
                  <b-form-input type="date" required v-model="formData.expired_account" placeholder="Expired Account"></b-form-input>
                </b-form-fieldset>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-6">
                <b-form-group id="exampleInputGroup3"
                              label="Currency *"
                              label-for="exampleInput3">
                  <b-form-select id="exampleInput3"
                                :options="currency"
                                required
                                v-model="formData.currency">
                  </b-form-select>
                </b-form-group>
                <!--<b-form-fieldset label="Currency *">
                  <b-form-input type="text" required v-model="formData.account_name" placeholder="Account Name"></b-form-input>
                </b-form-fieldset>-->
              </div>
              <div class="col-sm-6">
                <b-form-group id="exampleInputGroup3"
                              label="Country *"
                              label-for="exampleInput3">
                  <b-form-select id="exampleInput3"
                                :options="country"
                                required
                                v-model="formData.country">
                  </b-form-select>
                </b-form-group>
                <!--<b-form-fieldset label="Country *">
                  <b-form-input type="text" required v-model="formData.expired_account" placeholder="Expired Account"></b-form-input>
                </b-form-fieldset>-->
              </div>
            </div>

            <div class="row">
              <div class="col-sm-6">
                <b-form-fieldset label="Contact *">
                  <b-form-input type="text" required v-model="formData.contact" placeholder="Contact"></b-form-input>
                </b-form-fieldset>
              </div>
              <div class="col-sm-6">
                <b-form-fieldset label="Email *">
                  <b-form-input type="email" required v-model="formData.email" placeholder="Email"></b-form-input>
                </b-form-fieldset>
              </div>
            </div>         

            <div _slot="footer">
              <b-button type="reset" @click="onCancel()" size="sm" variant="default"><i class="fa fa-ban"></i> Cancel</b-button>
              <b-button type="submit" size="sm" variant="primary"><i class="fa fa-dot-circle-o"></i> Submit</b-button>
            </div>

          </form>
        </b-card>
      </div><!--/.col-->
    </div><!--/.row-->
  </div>
</template>

<script>
  import {createData,editData,updateData} from '../../../../../api/stockBalance/bankAccount'
  import {fetchCountryList} from '../../../../../api/setupMaster/country'
  import {fetchCurrencyList} from '../../../../../api/setupMaster/currency'

  import Flash from '../../../../../services/flash'
  export default {
    props:['id'],
    data(){
      return {
        backUrl:'/stocks/bank-account',
        name:'Bank Account Form',
        formData:{
          bank_name:'',
          currency:null,
          country:null,
          account_no:'',
          account_name:'',
          expired_account:'',
          contact:'',
          email:'',
          merchant_id:'',
          merchant_name:''
        },
        flash: Flash.state,
        country:[{text:'Choose Country', value: null }],
        currency:[{text:'Choose Currency', value: null }]
      }
    },
    
    created(){
      if(this.id){
        Flash.setLoading(true)
			  this.getData();
      }
      this.getAllCountry()
      this.getAllCurrency()
		},
    methods: {
      clearForm(){
        this.formData.bank_name = '',
        this.formData.currency = null,
        this.formData.country = null,
        this.formData.account_no = '',
        this.formData.account_name = '',
        this.formData.expired_account = '',
        this.formData.contact = '',
        this.formData.email = '',
        this.formData.merchant_id = '',
        this.formData.merchant_name = ''
      },
      getAllCountry() {
        fetchCountryList().then(response => {
          // this.datatable = response
          for(let x=0 ; x <= response['data'].length-1 ; x++){
            let data = {'text':response['data'][x]['name'],'value':response['data'][x]['name']}
            this.country.push(data)
          }
        })
      },
      getAllCurrency() {
        fetchCurrencyList().then(response => {
          // this.datatable = response
          for(let x=0 ; x <= response['data'].length-1 ; x++){
            let data = {'text':response['data'][x]['code'],'value':response['data'][x]['title']}
            this.currency.push(data)
          }
        })
      },
      getData() {
        editData(this.id).then(response => {
          // fetch all form data
          this.formData.bank_name = response['data']['bank_name']
          this.formData.currency = response['data']['currency']
          this.formData.country = response['data']['country']
          this.formData.account_no = response['data']['account_no']
          this.formData.account_name = response['data']['account_name']
          this.formData.expired_account = response['data']['expired_account']
          this.formData.contact = response['data']['contact']
          this.formData.email = response['data']['email']
          this.formData.merchant_id = response['data']['merchant_id']
          this.formData.merchant_name = response['data']['merchant_name']
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
