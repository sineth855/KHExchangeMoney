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
            <b-form-group id="exampleInputGroup3"
                          label="Country *"
                          label-for="exampleInput3">
              <b-form-select id="exampleInput3"
                            :options="country"
                            required
                            v-model="formData.country_id">
              </b-form-select>
            </b-form-group>

            <b-form-group id="exampleInputGroup3"
                          label="Currency *"
                          label-for="exampleInput3">
              <b-form-select id="exampleInput3"
                            :options="currency"
                            required
                            v-model="formData.currency_id">
              </b-form-select>
            </b-form-group>

            <b-form-group id="exampleInputGroup3"
                          label="Voucher *"
                          label-for="exampleInput3">
              <b-form-select id="exampleInput3"
                            :options="voucher"
                            required
                            v-model="formData.voucher_id">
              </b-form-select>
            </b-form-group>

            <b-form-fieldset label="Value *">
              <b-form-input type="number" required v-model="formData.name" placeholder="Value"></b-form-input>
            </b-form-fieldset>

            <b-form-fieldset label="Order Level *">
              <b-form-input type="number" required v-model="formData.order_level" placeholder="Order Level"></b-form-input>
            </b-form-fieldset>
            
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
  import {createData,editData,updateData} from '../../../../../api/setupmaster/amountTopUp'
  import {fetchCountryList} from '../../../../../api/setupmaster/country'
  import {fetchCurrencyList} from '../../../../../api/setupmaster/currency'
  import {fetchVoucherList} from '../../../../../api/vouchers/voucher'
  import Flash from '../../../../../services/flash'
  export default {
    props:['id'],
    data(){
      return {
        backUrl:'/amount-top-up',
        name:'Amount Top Up Form',
        formData:{
          name:'',
          country_id:null,
          currency_id:null,
          voucher_id:null,
          order_level:0
        },
        flash: Flash.state,
        country:[],
        voucher:[],
        currency:[],
        country: [{text:'Select Country', value: null }],
        voucher: [{text:'Select Voucher', value: null }],
        currency: [{text:'Select Currency', value: null }]
      }
    },
    
    created(){
      if(this.id){
        Flash.setLoading(true)
			  this.getData();
      }
      this.getAllCountry()
      this.getAllCurrency()
      this.getAllVouchers()
		},
    methods: {
      clearForm(){
        this.formData.name= ''
        this.formData.country_id= null
        this.formData.currency_id= null
        this.formData.voucher_id= null
      },
      getData() {
        editData(this.id).then(response => {
          // fetch all form data
          this.formData.name = response['data']['name']
          this.formData.country_id = response['data']['country_id']
          this.formData.currency_id = response['data']['currency_id']
          this.formData.voucher_id = response['data']['voucher_id']
          this.formData.order_level = response['data']['order_level']
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
      getAllVouchers() {
        fetchVoucherList().then(response => {
          // this.datatable = response
          for(let x=0 ; x <= response['data'].length-1 ; x++){
            let data = {'text':response['data'][x]['name'],'value':response['data'][x]['voucher_id']}
            this.voucher.push(data)
          }
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
