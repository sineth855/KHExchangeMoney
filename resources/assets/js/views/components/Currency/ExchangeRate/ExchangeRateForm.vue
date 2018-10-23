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
                              label="From Currency *"
                              label-for="exampleInput3">
                  <b-form-select id="exampleInput3"
                                :options="currency"
                                required
                                v-model="formData.from_currency_id">
                  </b-form-select>
                </b-form-group>
              </div>
              <div class="col-sm-6">
                <b-form-group id="exampleInputGroup3"
                              label="To Currency *"
                              label-for="exampleInput3">
                  <b-form-select id="exampleInput3"
                                :options="currency"
                                required
                                v-model="formData.to_currency_id">
                  </b-form-select>
                </b-form-group>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-6">
                <b-form-fieldset label="Buy In *">
                  <b-form-input type="text" required id="buy_in" v-model="formData.buy_in" placeholder="Buy In"></b-form-input>
                </b-form-fieldset>
              </div><!--/.col-->
              <div class="col-sm-6">
                <b-form-fieldset label="Sell Out *">
                  <b-form-input type="text" required id="sell_out" v-model="formData.sell_out" placeholder="Sell Out"></b-form-input>
                </b-form-fieldset>
              </div><!--/.col-->
            </div><!--/.row-->

            <div class="row">
              <div class="col-sm-6">
                <b-form-fieldset label="Rate">
                  <b-form-input type="text" id="rate" v-model="formData.rate" placeholder="Rate"></b-form-input>
                </b-form-fieldset>
              </div><!--/.col-->
            </div><!--/.row-->
            
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
  import {createData,editData,updateData} from '../../../../../api/currency/exchangeRate'
  import Flash from '../../../../../services/flash'
  import {fetchCurrencyList} from '../../../../../api/setupmaster/currency'
  export default {
    props:['id'],
    data(){
      return {
        backUrl:'/exchange-rate',
        name:'Exchange Rate Form',
        formData:{
          from_currency_id:null,
          to_currency_id:null,
          buy_in:'',
          sell_out:'',
          rate:''
        },
        flash: Flash.state,
        currency:[{text:'Choose Currency', value: null }],
      }
    },
    
    created(){
      if(this.id){
        Flash.setLoading(true)
			  this.getData();
      }
      this.getAllCurrency()
		},
    methods: {
      getAllCurrency() {
        fetchCurrencyList().then(response => {
          // this.datatable = response
          for(let x=0 ; x <= response['data'].length-1 ; x++){
            let data = {'text':response['data'][x]['code'],'value':response['data'][x]['currency_id']}
            this.currency.push(data)
          }
        })
      },
      clearForm(){
        this.formData = {}
      },
      getData() {
        editData(this.id).then(response => {
          // fetch all form data
          this.formData.from_currency_id = response['data']['from_currency_id']
          this.formData.to_currency_id = response['data']['to_currency_id']
          this.formData.buy_in = response['data']['buy_in']
          this.formData.sell_out = response['data']['sell_out']
          this.formData.rate = response['data']['rate']
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
