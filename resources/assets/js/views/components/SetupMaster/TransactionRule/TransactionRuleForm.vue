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
                <b-form-fieldset label="Transaction Rule Name *">
                  <b-form-input type="text" required v-model="formData.transaction_rule_name" placeholder="Transaction Rule Name"></b-form-input>
                </b-form-fieldset>
              </div>

              <div class="col-sm-6">
                <b-form-fieldset label="Amount Limit *">
                  <b-form-input type="number" required v-model="formData.amount_limit" placeholder="Amount Limit"></b-form-input>
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
                                    v-model="formData.currency_id">
                    </b-form-select>
                    </b-form-group>
                </div>

                <div class="col-sm-6">
                    <b-form-group id="exampleInputGroup3"
                        label="Rule Type *"
                        label-for="exampleInput3">
                      <b-form-select id="exampleInput3"
                                      :options="ruleType"
                                      required
                                      v-model="formData.rule_type_id">
                      </b-form-select>
                    </b-form-group>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <b-form-group id="exampleInputGroup3"
                        label="Restrict Type *"
                        label-for="exampleInput3">
                    <b-form-select id="exampleInput3"
                                    :options="restrictType"
                                    required
                                    v-model="formData.restrict_type_id">
                    </b-form-select>
                    </b-form-group>
                </div>

                <div class="col-sm-6">
                    <b-form-group id="exampleInputGroup3"
                        label="Delivery Method *"
                        label-for="exampleInput3">
                    <b-form-select id="exampleInput3"
                                    :options="deliveryMethod"
                                    required
                                    v-model="formData.delivery_method_id">
                    </b-form-select>
                    </b-form-group>
                </div>
            </div>

            <b-form-group id="exampleInputGroup3"
                label="Country *"
                label-for="exampleInput3">
            <b-form-select id="exampleInput3"
                            :options="country"
                            required
                            v-model="formData.country_id">
            </b-form-select>
            </b-form-group>

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
  import {createData,editData,updateData} from '../../../../../api/setupmaster/transactionRule'
  import {fetchCountryList} from '../../../../../api/setupmaster/country'
  import {fetchRuleTypeList} from '../../../../../api/setupmaster/ruleType'
  import {fetchRestrictTypeList} from '../../../../../api/setupmaster/restrictType'
  import {fetchDeliveryMethodList} from '../../../../../api/setupmaster/deliveryMethod'
  import {fetchCurrencyList} from '../../../../../api/setupmaster/currency'
  import Flash from '../../../../../services/flash'
  export default {
    props:['id'],
    data(){
      return {
        backUrl:'/transaction-rule',
        name:'Transaction Rule Form',
        formData:{
          transaction_rule_name:'',
          currency_id:null,
          country_id:null,
          rule_type_id:null,
          restrict_type_id:null,
          delivery_method_id:null,
          amount_limit:'',
          remark:''
        },
        flash: Flash.state,
        country: [{ text: 'Select Country', value: null }],
        currency: [{ text: 'Select Currency', value: null }],
        ruleType: [{ text: 'Select Rule Type', value: null }],
        restrictType: [{ text: 'Select Restrict Type', value: null }],
        deliveryMethod: [{ text: 'Select Delivery Method', value: null }],
      }
    },
    
    created(){
      if(this.id){
        Flash.setLoading(true)
			  this.getData();
      }
      this.getAllCurrency()
      this.getAllCountry()
      this.getAllRuleType()
      this.getAllRestrictType()
      this.getAllDeliveryMethod()
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
      getAllCountry() {
          fetchCountryList().then(response => {
            // this.datatable = response
            for(let x=0 ; x <= response['data'].length-1 ; x++){
                let data = {'text':response['data'][x]['name'],'value':response['data'][x]['country_id']}
                this.country.push(data)
            }
          })
      },
      getAllRuleType() {
          fetchRuleTypeList().then(response => {
            // this.datatable = response
            for(let x=0 ; x <= response['data'].length-1 ; x++){
                let data = {'text':response['data'][x]['name'],'value':response['data'][x]['rule_type_id']}
                this.ruleType.push(data)
            }
          })
      },
      getAllRestrictType() {
          fetchRestrictTypeList().then(response => {
            // this.datatable = response
            for(let x=0 ; x <= response['data'].length-1 ; x++){
                let data = {'text':response['data'][x]['name'],'value':response['data'][x]['restrict_type_id']}
                this.restrictType.push(data)
            }
          })
      },
      getAllDeliveryMethod() {
          fetchDeliveryMethodList().then(response => {
            // this.datatable = response
            for(let x=0 ; x <= response['data'].length-1 ; x++){
                let data = {'text':response['data'][x]['delivery_method'],'value':response['data'][x]['delivery_method_id']}
                this.deliveryMethod.push(data)
            }
          })
      },
      clearForm(){
        this.formData.transaction_rule_name = ''
        this.formData.country_id = null
        this.formData.rule_type_id = null
        this.formData.restrict_type_id = null
        this.formData.delivery_method_id = null
        this.formData.amount_limit = ''
        this.formData.remark = ''
      },
      getData() {
        editData(this.id).then(response => {
          // fetch all form data
          this.formData.transaction_rule_name = response['data']['transaction_rule_name']
          this.formData.currency_id = response['data']['currency_id'] 
          this.formData.country_id = response['data']['country_id']
          this.formData.rule_type_id = response['data']['rule_type_id']
          this.formData.restrict_type_id = response['data']['restrict_type_id']
          this.formData.delivery_method_id = response['data']['delivery_method_id']
          this.formData.amount_limit = response['data']['amount_limit']
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
