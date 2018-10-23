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
            <b-form-fieldset label="Name *">
              <b-form-input type="text" required v-model="formData.name" placeholder="Name"></b-form-input>
            </b-form-fieldset>

            <b-form-group id="exampleInputGroup3"
                          label="Currency *"
                          label-for="exampleInput3">
              <b-form-select id="exampleInput3"
                            :options="currency"
                            required
                            v-model="formData.currency_id">
              </b-form-select>
            </b-form-group>

            <b-form-fieldset label="Min Allowed Amount *">
              <b-form-input type="number" required v-model="formData.min_amount_allowed" placeholder="0.00"></b-form-input>
            </b-form-fieldset>

            <b-form-fieldset label="Max Allowed Amount *">
              <b-form-input type="number" required v-model="formData.max_amount_allowed" placeholder="0.00"></b-form-input>
            </b-form-fieldset>

            <b-form-fieldset label="Remark">
              <b-form-input type="text" required v-model="formData.remark" placeholder="Remark"></b-form-input>
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
  import {createData,editData,updateData} from '../../../../../api/setupmaster/accountRule'
  import {fetchCurrencyList} from '../../../../../api/setupmaster/currency'
  import Flash from '../../../../../services/flash'
  export default {
    props:['id'],
    data(){
      return {
        backUrl:'/merchant-account/account-rule',
        name:'Account Rule Form',
        formData:{
          currency_id:null,
          name:'',
          min_amount_allowed:'',
          max_amount_allowed:'',
          remark:'',
        },
        currency:[{text:'Choose Currency', value: null }],
        flash: Flash.state
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
      clearForm(){
        this.formData.currency_id = null
        this.formData.name = ''
        this.formData.min_amount_allowed = ''
        this.formData.max_amount_allowed = ''
        this.formData.remark = ''
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
          this.formData.currency_id = response['data']['currency_id']
          this.formData.name = response['data']['name']
          this.formData.min_amount_allowed = response['data']['min_amount_allowed']
          this.formData.max_amount_allowed = response['data']['max_amount_allowed']
          this.formData.remark = response['data']['remark']
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
