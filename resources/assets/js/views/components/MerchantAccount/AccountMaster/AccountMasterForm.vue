<template>
  <form id="form" method="post" enctype="multipart/form-data" @submit.prevent="onSubmit">
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
                <b-form-fieldset label="Merchant Name *">
                  <b-form-input type="text" required v-model="formData.merchant_name" placeholder="Merchant Name"></b-form-input>
                </b-form-fieldset>
              </div>
              <div class="col-sm-6">
                <b-form-fieldset label="MerchantID *">
                  <b-form-input type="text" required disabled id="merchant_id" v-model="formData.merchant_id" placeholder="Merchant ID"></b-form-input>
                </b-form-fieldset>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-6">
                <b-form-fieldset label="Expired Account *">
                  <input type="hidden" id="user_id" v-model="formData.user_id"/>
                  <b-form-input type="date" required id="expired_account" v-model="formData.expired_account" placeholder="Expired Account"></b-form-input>
                </b-form-fieldset>
              </div><!--/.col-->
              <div class="col-sm-6">
                <b-form-fieldset label="Date of Birth *">
                  <b-form-input type="date" required id="date_of_birth" v-model="formData.dob" placeholder="Date of Birth"></b-form-input>
                </b-form-fieldset>
              </div><!--/.col-->
            </div><!--/.row-->
            
            <div class="row">
              <div class="col-sm-6">
                <b-form-fieldset label="FirstName *">
                  <b-form-input type="text" required id="FirstName" v-model="formData.first_name" placeholder="First Name"></b-form-input>
                </b-form-fieldset>
              </div><!--/.col-->
              <div class="col-sm-6">
                <b-form-fieldset label="LastName *">
                  <b-form-input type="text" required id="last_name" v-model="formData.last_name" placeholder="Last Name"></b-form-input>
                </b-form-fieldset>
              </div><!--/.col-->
            </div><!--/.row-->

            <div class="row">
              <div class="col-sm-6">
                <b-form-fieldset label="Company">
                  <b-form-input type="text" id="company" v-model="formData.company" placeholder="Company"></b-form-input>
                </b-form-fieldset>
              </div><!--/.col-->
              <div class="col-sm-6">
                <b-form-fieldset label="Website">
                  <b-form-input type="text" id="website" v-model="formData.website" placeholder="Website"></b-form-input>
                </b-form-fieldset>
              </div><!--/.col-->
            </div><!--/.row-->

            <div class="row">
              <div class="col-sm-6">
                <b-form-fieldset label="Contact 1 *">
                  <b-form-input type="text" required id="telephone_1" v-model="formData.telephone_1" placeholder="Telephone 1"></b-form-input>
                </b-form-fieldset>
              </div><!--/.col-->
              <div class="col-sm-6">
                <b-form-fieldset label="Contact 2">
                  <b-form-input type="text" id="telephone_2" v-model="formData.telephone_2" placeholder="Telephone 1"></b-form-input>
                </b-form-fieldset>
              </div><!--/.col-->
            </div><!--/.row-->

            <div class="row">
              <div class="col-sm-6">
                <b-form-fieldset label="Email *">
                  <b-form-input type="email" required id="email" v-model="formData.email" placeholder="Email"></b-form-input>
                </b-form-fieldset>
              </div><!--/.col-->
              <div class="col-sm-6">
                <!--<b-form-fieldset label="Country">
                  <b-form-input type="text" required id="country" v-model="formData.country" placeholder="Country"></b-form-input>
                </b-form-fieldset>-->
                <b-form-group id="exampleInputGroup3"
                              label="Country *"
                              label-for="exampleInput3">
                  <b-form-select id="exampleInput3"
                                :options="country"
                                required
                                v-model="get_country">
                  </b-form-select>
                </b-form-group>
                <input placeholder="test" type="hidden" id="country" v-model="formData.country"/>
                <input type="hidden" id="country_id" v-model="formData.country_id"/>
                <input type="hidden" id="country_code" v-model="formData.country_code"/>
              </div><!--/.col-->
            </div><!--/.row-->

            <div class="row">
              <div class="col-sm-6">
                <b-form-fieldset label="Province">
                  <b-form-input type="text" id="province" v-model="formData.province" placeholder="Province"></b-form-input>
                </b-form-fieldset>
              </div><!--/.col-->
              <div class="col-sm-6">
                <b-form-fieldset label="City">
                  <b-form-input type="text" id="city" v-model="formData.city" placeholder="City"></b-form-input>
                </b-form-fieldset>
              </div><!--/.col-->
            </div><!--/.row-->

            <div class="row">
              <div class="col-sm-6">
                <b-form-fieldset label="Address 1 *">
                  <b-form-input type="text" required id="address_1" v-model="formData.address_1" placeholder="Address 1"></b-form-input>
                </b-form-fieldset>
              </div><!--/.col-->
              <div class="col-sm-6">
                <b-form-fieldset label="Address 2">
                  <b-form-input type="text" id="address_2" v-model="formData.address_2" placeholder="Address 2"></b-form-input>
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
        <div class="col-sm-4">
          <b-card>
            <div slot="header">
              <strong>Photo</strong>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <b-form-fieldset>
                  <span v-if="imagePreview.length > 0">
                    <img width="80px" :src="imagePreview"/><br/>
                  </span>
                  <!--<input type="file" id="image" name="image" v-on:change="previewImage" accept="image/*">-->
                  <b-form-file id="image" name="image" v-on:change="previewImage" accept="image/*" placeholder="Choose a file..."></b-form-file>
                  <!--<b-form-input type="file" v-model="formData.root_img" id="image" @change="previewImage($event)" accept="image/*" name="image"></b-form-input>-->
                </b-form-fieldset>

                <input type="hidden" v-model="formData.hidden_image"/>
              </div>
            </div>
            <!--<div _slot="footer">
              <b-button type="reset" @click="onCancel()" size="sm" variant="default"><i class="fa fa-ban"></i> Cancel</b-button>
              <b-button type="submit" size="sm" variant="primary"><i class="fa fa-dot-circle-o"></i> Submit</b-button>
            </div>-->
          </b-card>
          
          <b-card>
            <div slot="header">
              <strong>Authentication Login</strong>
            </div>
            
            <div class="row">
              <div class="col-sm-12">
                <b-form-fieldset label="User Login By Phone*">
                  <b-input-group>
                    <b-form-input type="text" v-model="formData.username" placeholder="eg: 081777888"></b-form-input>
                    <!-- Attach Right button -->
                    <b-input-group-button slot="left">
                      <b-button id="lbl_country_code" variant="primary">+855</b-button>
                    </b-input-group-button>
                  </b-input-group>
                </b-form-fieldset>
              </div>

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
            <!--<div _slot="footer">
              <b-button type="reset" @click="onCancel()" size="sm" variant="default"><i class="fa fa-ban"></i> Cancel</b-button>
              <b-button type="submit" size="sm" variant="primary"><i class="fa fa-dot-circle-o"></i> Submit</b-button>
            </div>-->
          </b-card>
        </div><!--/.col-->
      </div><!--/.row-->
    </div>
  </form>
</template>

<script>
  import {createData,editData,updateData} from '../../../../../api/merchantAccount/merchantAccount'
  import {getCountryById,fetchCountryList} from '../../../../../api/setupmaster/country'
  import Flash from '../../../../../services/flash'
  export default {
    props:['id'],
    data(){
      return {
        backUrl:'/merchant-account/account-master',
        name:'Account Master Form',
        get_country:null,
        imagePreview:'',
        file:'',
        formData:{
          root_img:'',
          image:'',
          user_id:'',
          merchant_name:'',
          merchant_id:'',
          email:'',
          first_name:'',
          last_name:'',
          telephone_1:'',
          telephone_2:'',
          expired_account:'',
          dob:'',
          company:'',
          company_id:'',
          website:'',
          country:'',
          province:'',
          city:'',
          address_1:'',
          address_2:'',
          username:'',
          password:'',
          confirm_password:'',
          password_hidden:'',
          country_code:''      
        },
        flash: Flash.state,
        country:[{text:'Select Country', value: null }],
      }
    },
    watch: {
      get_country: function(val){
        this.getCountry(val)
      }
    },
    created(){
      this.getAllCountry()
      if(this.id){
        Flash.setLoading(true)
			  this.getData()
      }else{
        this.formData.merchant_id = Math.floor(Math.random() * (9999999999 - 1 + 1)) + 1
      }
		},
    methods: {
      previewImage: function(event) {
        this.formData.hidden_image = ''
        // Reference to the DOM input element
        var input = event.target;
        // Ensure that you have a file before attempting to read it
        if (input.files && input.files[0]) {
            // create a new FileReader to read this image and convert to base64 format
            var reader = new FileReader();
            // Define a callback function to run, when FileReader finishes its job
            reader.onload = (e) => {
                // Note: arrow function used here, so that "this.imagePreview" refers to the imageData of Vue component
                // Read image as base64 and set to imageData
                this.imagePreview = e.target.result;
            }
            // Start the reader job - read file as a data url (base64 format)
            reader.readAsDataURL(input.files[0]);
        }
      },
      clearForm(){
        this.formData.merchant_name= ""
        this.formData.merchant_id = Math.floor(Math.random() * (9999999999 - 1 + 1)) + 1
        this.formData.email = ""
        this.formData.first_name = ""
        this.formData.last_name = ""
        this.formData.telephone_1 = ""
        this.formData.telephone_2 = ""
        this.formData.expired_account = ""
        this.formData.dob = ""
        this.formData.company = null
        this.formData.website = ""
        this.formData.country = null
        this.formData.province = ''
        this.formData.city = ''
        this.formData.address_1 = ""
        this.formData.address_2 = ""
        this.formData.username = ""
        this.formData.password_hidden = ""
      },
      getCountry(country_id){
        getCountryById(country_id).then(response => {
          // alert(response['data']['name'])
          this.formData.country = response['data']['name']
          this.formData.country_id = response['data']['country_id']
          this.formData.country_code = response['data']['country_code']
          document.getElementById("lbl_country_code").innerHTML = '+'+response['data']['country_code']
          this.formData.username = '+'+response['data']['country_code']+this.formData.telephone_1
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
      getData() {
        editData(this.id).then(response => {
          // fetch all form data
          this.formData.user_id = response['data'][0]['user_id']
          this.formData.merchant_name = response['data'][0]['merchant_name']
          this.formData.merchant_id = response['data'][0]['merchant_id']
          this.formData.email = response['data'][0]['email']
          this.formData.telephone_1 = response['data'][0]['telephone_1']
          this.formData.telephone_2 = response['data'][0]['telephone_2']
          this.formData.first_name = response['data'][0]['first_name']
          this.formData.last_name = response['data'][0]['last_name']
          this.formData.expired_account = response['data'][0]['expired_account']
          this.formData.dob = response['data'][0]['dob']
          this.formData.company = response['data'][0]['company']
          this.formData.website = response['data'][0]['website']
          this.formData.country_id = response['data'][0]['country_id']
          this.get_country = response['data'][0]['country_id']
          this.formData.country_code = response['data'][0]['country_code']
          this.formData.country = response['data'][0]['country']
          this.formData.address_1 = response['data'][0]['address_1']
          this.formData.address_2 = response['data'][0]['address_2']
          this.formData.username = response['data'][0]['username']
          this.formData.password_hidden = response['data'][0]['password']
        })
      },
      onCancel(){
        this.$router.push(this.backUrl)
      },
      onSubmit (event) {
        event.preventDefault()
        // var element = document.getElementById("topDiv");
        // var top = element.offsetTop;
        // window.scrollTo(0, top);

        // var form = event.target;
        // this.formData.image = new FormData(form)
        // this.formData.image.append('imageTest',this.formData)
        // If has props edit data
        if(this.id){
          if(this.formData.password==''){
            
          }else{
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
          if(this.formData.password==''){
            Flash.setError('Password can\'t be null!')
            return false
          }else{
            if(this.formData.password!=this.formData.confirm_password){
              this.formData.password = ' '
              this.formData.confirm_password = ' '
              Flash.setError('Password is not matched!')
              return false
            }
          }
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
