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
  import {createData,editData,updateData} from '../../../../../api/setupmaster/deliveryMethodType'
  import Flash from '../../../../../services/flash'
  export default {
    props:['id'],
    data(){
      return {
        backUrl:'/delivery-method-type',
        name:'Delivery Method Type Form',
        formData:{
          name:''
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
        this.formData.name= ''
      },
      getData() {
        editData(this.id).then(response => {
          // fetch all form data
          this.formData.name = response['data']['name']
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
