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
            <b-form-fieldset label="Title *">
              <b-form-input type="text" required v-model="formData.title" placeholder="Title"></b-form-input>
            </b-form-fieldset>

            <b-form-fieldset label="Code *">
              <b-form-input type="text" maxlength="3" required v-model="formData.code" placeholder="Code"></b-form-input>
            </b-form-fieldset>

            <b-form-fieldset label="Symbol Left">
              <b-form-input type="text" v-model="formData.symbol_left" placeholder="Symbol Left"></b-form-input>
            </b-form-fieldset>

            <b-form-fieldset label="Symbol Right">
              <b-form-input type="text" v-model="formData.symbol_right" placeholder="Symbol Right"></b-form-input>
            </b-form-fieldset>

            <b-form-fieldset label="Decimal Place">
              <b-form-input type="text" v-model="formData.decimal_place" placeholder="Decimal Place"></b-form-input>
            </b-form-fieldset>

            <b-form-fieldset label="Exchange Rate *">
              <b-form-input type="text" required v-model="formData.value" placeholder="Exchange Rate"></b-form-input>
            </b-form-fieldset>

            <b-form-fieldset>
              <b-form-checkbox v-model="formData.is_default"> Is Default?</b-form-checkbox>
            </b-form-fieldset>

            <b-form-fieldset>
              <b-form-checkbox v-model="formData.status"> Is Active?</b-form-checkbox>
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
  import {createData,editData,updateData} from '../../../../../api/setupmaster/currency'
  import Flash from '../../../../../services/flash'
  export default {
    props:['id'],
    data(){
      return {
        backUrl:'/currency',
        name:'Currency Form',
        formData:{
          title:'',
          code:'',
          symbol_left:'',
          symbol_right:'',
          decimal_place:'',
          value:'',
          is_default:false,
          status:true
        },
        flash: Flash.state
      }
    },
    
    created(){
      if(this.id){
        Flash.setLoading(true)
			  this.getData();
      }
		},
    methods: {
      clearForm(){
        this.formData.title= ''
        this.formData.code= ''
        this.formData.symbol_left= ''
        this.formData.symbol_right= ''
        this.formData.decimal_place= ''
        this.formData.value= ''
        this.formData.is_default= false
        this.formData.status= true
      },
      getData() {
        editData(this.id).then(response => {
          // fetch all form data
          this.formData.title = response['data']['title']
          this.formData.code = response['data']['code']
          this.formData.symbol_left = response['data']['symbol_left']
          this.formData.symbol_right = response['data']['symbol_right']
          this.formData.decimal_place = response['data']['decimal_place']
          this.formData.value = response['data']['value']
          if(response['data']['is_default']==1){
            this.formData.is_default = true
          }else{
            this.formData.is_default = false
          }
          if(response['data']['status']==1){
            this.formData.status = true
          }else{
            this.formData.status = false
          }
        })
      },
      onCancel(){
        this.$router.push(this.backUrl)
      },
      onSubmit (event) {
        if(this.formData.is_default){
          this.formData.is_default = 1
        }else{
          this.formData.is_default = 0
        }
        let newFormData = this.formData
        // If has props edit data
        if(this.id){
          updateData(newFormData,this.id).then(response => {
            if(response.success == true){
              Flash.setSuccess(response.message)
            }else{
              Flash.setError(response.message)
            }
          })
        }else{          
          createData(newFormData).then(response => {
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
