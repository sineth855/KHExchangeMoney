<template>
  <form id="form" @submit.prevent="onSubmit">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-sm-12">
          <b-alert v-if="flash.success" variant="success" show>{{ flash.success }}</b-alert>
          <b-alert v-if="flash.error" variant="danger" show>{{ flash.error }}</b-alert>
        </div>
        <div class="col-sm-8">
          <b-card>
            <div slot="header">
              <strong>{{name}}</strong> <small>Form</small>
            </div>
            
              <div class="row">
                <div class="col-sm-6">
                  <b-form-fieldset label="First Name *">
                    <b-form-input type="text" required v-model="formData.first_name" placeholder="First Name"></b-form-input>
                  </b-form-fieldset>
                </div>
                <div class="col-sm-6">
                    <b-form-fieldset label="Last Name *">
                      <b-form-input type="text" required v-model="formData.last_name" placeholder="First Name"></b-form-input>
                    </b-form-fieldset>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-6">
                  <b-form-fieldset label="Date of Birth">
                    <b-form-input type="date" v-model="formData.dob" placeholder="DOB"></b-form-input>
                  </b-form-fieldset>
                </div>
                <div class="col-sm-6">
                    <b-form-fieldset label="Address 1">
                      <b-form-input type="text" v-model="formData.address_1" placeholder="Address"></b-form-input>
                    </b-form-fieldset>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-6">
                  <b-form-fieldset label="Address 2">
                    <b-form-input type="text" v-model="formData.address_2" placeholder="Address"></b-form-input>
                  </b-form-fieldset>
                </div>
                <div class="col-sm-6">
                    <b-form-fieldset label="Primary Email *">
                      <b-form-input type="text" required v-model="formData.email" placeholder="Email 1"></b-form-input>
                    </b-form-fieldset>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-6">
                  <b-form-fieldset label="Secondary Email">
                    <b-form-input type="text" v-model="formData.email_2" placeholder="Email 2"></b-form-input>
                  </b-form-fieldset>
                </div>
                <div class="col-sm-6">
                    <b-form-fieldset label="Telephone 1 *">
                      <b-form-input type="text" required v-model="formData.telephone_1" placeholder="Telephone 1"></b-form-input>
                    </b-form-fieldset>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-6">
                  <b-form-fieldset label="Telephone 2">
                    <b-form-input type="text" v-model="formData.telephone_2" placeholder="Telephone 2"></b-form-input>
                  </b-form-fieldset>
                </div>
                <div class="col-sm-6">
                    <b-form-fieldset label="Company">
                      <b-form-input type="text" v-model="formData.company" placeholder="Company"></b-form-input>
                    </b-form-fieldset>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-6">
                  <b-form-fieldset label="Country">
                    <b-form-input type="text" v-model="formData.country" placeholder="Company"></b-form-input>
                  </b-form-fieldset>
                </div>
                <div class="col-sm-6">
                    <b-form-fieldset label="Province">
                      <b-form-input type="text" v-model="formData.province" placeholder="Company"></b-form-input>
                    </b-form-fieldset>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-6">
                  <b-form-fieldset label="City">
                    <b-form-input type="text" v-model="formData.city" placeholder="Company"></b-form-input>
                  </b-form-fieldset>
                </div>
              </div>
          </b-card>
        </div><!--/.col-->
        <!--col-->
        <div class="col-sm-4">
          <b-card>
            <div slot="header">
              <strong>Authentication Login</strong>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <b-form-fieldset label="Password *">
                  <input type="hidden" v-model="formData.password_hidden" placeholder="Password Hidden"/>
                  <b-form-input type="password" v-model="formData.password" placeholder="Password"></b-form-input>
                </b-form-fieldset>
              </div>
              <div class="col-sm-12">
                <b-form-fieldset label="New Password *">
                  <b-form-input type="password" v-model="formData.confirm_password" placeholder="New Password"></b-form-input>
                </b-form-fieldset>
              </div>
            </div>
            <div _slot="footer">
              <b-button type="reset" @click="onCancel()" size="sm" variant="default"><i class="fa fa-ban"></i> Cancel</b-button>
              <b-button type="submit" size="sm" variant="primary"><i class="fa fa-dot-circle-o"></i> Submit</b-button>
            </div>
          </b-card>
        </div><!--/.col-->
      </div><!--/.row-->
    </div>
  </form>
</template>

<script>
  import {createData,editData,updateData} from '../../../../../api/users/user'
  import Flash from '../../../../../services/flash'
  export default {
    props:['id'],
    data(){
      return {
        backUrl:'/user',
        name:'User',
        formData:{
          first_name:'',
          last_name:'',
          dob:'',
          address_1:'',
          address_2:'',
          email:'',
          email_2:'',
          telephone_1:'',
          telephone_2:'',
          company:'',
          country:'',
          province:'',
          city:'',
          password:'',
          confirm_password:'',
          password_hidden:'',
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
        this.formData.name= ' '
      },
      getData() {
        editData(this.id).then(response => {
          // fetch all form data
          this.formData.first_name = response['data']['first_name']
          this.formData.last_name = response['data']['last_name']
          this.formData.dob = response['data']['dob']
          this.formData.address_1 = response['data']['address_1']
          this.formData.address_2 = response['data']['address_2']
          this.formData.email = response['data']['email']
          this.formData.email_2 = response['data']['email_2']
          this.formData.telephone_1 = response['data']['telephone_1']
          this.formData.telephone_2 = response['data']['telephone_2']
          this.formData.company = response['data']['company']
          this.formData.country = response['data']['country']
          this.formData.province = response['data']['province']
          this.formData.city = response['data']['city']
          this.formData.password_hidden = response['data']['password']
        })
      },
      onCancel(){
        this.$router.push(this.backUrl)
      },
      onSubmit (event) {
        // If has props edit data
        if(this.id){
          if(this.formData.password!=''){
            if(this.formData.password!=this.formData.confirm_password){
              this.formData.password = ' '
              this.formData.confirm_password = ' '
              Flash.setError('Password is not matched!')
              return false
            }
          }
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
