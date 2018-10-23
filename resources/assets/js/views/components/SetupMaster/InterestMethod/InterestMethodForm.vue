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
                <b-form-fieldset label="Name *">
                  <b-form-input type="text" required v-model="formData.name" placeholder="Name"></b-form-input>
                </b-form-fieldset>
              </div>
              <div class="col-sm-6">
                <b-form-fieldset label="Minimum Cash *">
                  <b-form-input type="text" required v-model="formData.minimun_cash" placeholder="Minimum Cash"></b-form-input>
                </b-form-fieldset>
              </div>
            </div>
            
            <div class="row">
              <div class="col-sm-6">
                <b-form-fieldset label="Maximum Cash *">
                  <b-form-input type="text" required v-model="formData.maxinum_cash" placeholder="Maximum Cash"></b-form-input>
                </b-form-fieldset>
              </div>
              <div class="col-sm-6">
                <b-form-fieldset label="Fix Charge *">
                  <b-form-input type="text" required v-model="formData.fix_charge" placeholder="Fix Charge"></b-form-input>
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
                <b-form-fieldset label="Percentage *">
                  <b-form-input type="number" required v-model="formData.percentage" placeholder="%"></b-form-input>
                </b-form-fieldset>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-6">
                <b-form-fieldset label="Processing Period *">
                  <b-form-input type="text" required v-model="formData.processing_period" placeholder="Processing Period"></b-form-input>
                </b-form-fieldset>
              </div>
              <div class="col-sm-6">
                <b-form-group id="exampleInputGroup3"
                              label="Term Day *"
                              label-for="exampleInput3">
                  <b-form-select id="exampleInput3"
                                :options="termday"
                                required
                                v-model="formData.term_day_id">
                  </b-form-select>
                </b-form-group>
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
  import {createData,editData,updateData} from '../../../../../api/setupmaster/interestMethod'
  import {fetchTermDayList} from '../../../../../api/setupmaster/termDay'
  import {fetchCurrencyList} from '../../../../../api/setupmaster/currency'
  import Flash from '../../../../../services/flash'
  export default {
    props:['id'],
    data(){
      return {
        backUrl:'/interest-method',
        name:'Interest Method Form',
        formData:{
          name:'',
          minimun_cash:'',
          maxinum_cash:'',
          fix_charge:0,
          currency_id: null,
          percentage:'',
          processing_period:'',
          term_day_id: null
        },
        flash: Flash.state,
        currency:[{text:'Choose Currency', value: null }],
        termday:[{text:'Choose Term Day', value: null }],
      }
    },
    
    created(){
      if(this.id){
        Flash.setLoading(true)
			  this.getData();
      }
      this.getAllTermDay()
      this.getAllCurrency()
		},
    methods: {
      getAllTermDay() {
        fetchTermDayList().then(response => {
          // this.datatable = response
          for(let x=0 ; x <= response['data'].length-1 ; x++){
            let data = {'text':response['data'][x]['name'],'value':response['data'][x]['term_day_id']}
            this.termday.push(data)
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
      clearForm(){
        this.formData.name = ''
        this.formData.minimun_cash = ''
        this.formData.maxinum_cash = ''
        this.formData.currency_id = null
        this.formData.fix_charge = 0
        this.formData.percentage = ''
        this.formData.processing_period = ''
        this.formData.term_day_id = null
      },
      getData() {
        editData(this.id).then(response => {
          // fetch all form data
          this.formData.name = response['data']['name']
          this.formData.minimun_cash = response['data']['minimun_cash']
          this.formData.maxinum_cash = response['data']['maxinum_cash']
          this.formData.fix_charge = response['data']['fix_charge']
          this.formData.currency_id = response['data']['currency_id']
          this.formData.percentage = response['data']['percentage']
          this.formData.processing_period = response['data']['processing_period']
          this.formData.term_day_id = response['data']['term_day_id']
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
