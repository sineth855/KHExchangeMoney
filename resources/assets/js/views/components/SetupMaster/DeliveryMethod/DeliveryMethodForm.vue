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
          <form id="form" method="post" enctype="multipart/form-data" @submit.prevent="onSubmit">
            <b-form-fieldset label="Image *">
              <span v-if="imagePreview.length > 0">
                <img width="80px" alt="Image" :src="imagePreview"/><br/>
              </span>
              <!--<input type="file" id="image" name="image" v-on:change="previewImage" accept="image/*">-->
              <b-form-file id="image" name="image" v-on:change="previewImage" accept="image/*" placeholder="Choose a file..."></b-form-file>
            </b-form-fieldset>

            <input type="hidden" v-model="formData.hidden_image"/>
            <b-form-group id="exampleInputGroup3"
                          label="Delivery Method Type"
                          label-for="exampleInput3">
              <b-form-select id="exampleInput3"
                            :options="deliveryMethodType"
                            required
                            v-model="formData.delivery_method_type_id">
              </b-form-select>
            </b-form-group>

            <b-form-fieldset label="Method Name *">
              <b-form-input type="text" required v-model="formData.name" placeholder="Method Name"></b-form-input>
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
  import {createData,editData,updateData} from '../../../../../api/setupmaster/deliveryMethod'
  import {fetchDeliveryMethodTypeList} from '../../../../../api/setupmaster/deliveryMethodType'
  import {uploadFile} from '../../../../../services/fileUpload'
  import Flash from '../../../../../services/flash'
  export default {
    props:['id'],
    data(){
      return {
        backUrl:'/delivery-method',
        name:'Delivery Method Form',
        imagePreview:'',
        formData:{
          root_img:'',
          image:'',
          delivery_method_type_id:null,
          name:'',
          order_level:0
        },
        flash: Flash.state,
        deliveryMethodType: [
          { text: 'Select Delivery Method Type', value: null }
        ],
      }
    },
    
    created(){
      if(this.id){
        Flash.setLoading(true)
			  this.getData();
      }
      this.getAllDeliveryMethodType()
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
        this.formData = {}
      },
      getAllDeliveryMethodType() {
          fetchDeliveryMethodTypeList().then(response => {
              for(let x = 0 ; x <= response['data'].length-1 ; x++){
                  let data = {'text':response['data'][x]['name'],'value':response['data'][x]['delivery_method_type_id']}
                  this.deliveryMethodType.push(data)
              }
          })
      },
      getData() {
        editData(this.id).then(response => {
          // fetch all form data
          this.formData.delivery_method_type_id = response['data']['delivery_method_type_id']
          this.formData.name = response['data']['name']
          this.formData.order_level = response['data']['order_level']
          this.imagePreview = response['data']['image']
          this.formData.hidden_image = response['data']['image']
        })
      },
      onCancel(){
        this.$router.push(this.backUrl)
      },
      
      onSubmit (event) {
        var form = event.target;
        var data = new FormData(form);
        let formData = {};
        // If has props edit data
        if(this.id){
          if(this.formData.hidden_image != ''){
            // alert("update hidden")
            // return false
            formData = {
              'delivery_method_type_id':this.formData.delivery_method_type_id,
              'image':this.formData.hidden_image,
              'name':this.formData.name,
              'order_level':this.formData.order_level
            }
            updateData(formData,this.id).then(response => {
              if(response.success == true){
                Flash.setSuccess(response.message)
              }else{
                Flash.setError(response.message)
              }
            })
            // return false
          }else{
            // alert("update image")
            // return false
            uploadFile(data).then(response => {
              if(response.success == true){
                formData = {
                  'delivery_method_type_id':this.formData.delivery_method_type_id,
                  'image':response.data.image_url,
                  'name':this.formData.name,
                  'order_level':this.formData.order_level
                }
                updateData(formData,this.id).then(response => {
                  if(response.success == true){
                    Flash.setSuccess(response.message)
                  }else{
                    Flash.setError(response.message)
                  }
                })
                Flash.setSuccess(response.message)
              }else{
                Flash.setError(response.message)
                return false
              }
            }).catch(e => {
                Flash.setError("Error while trying to upload file!")
                this.showSendMoneyForm = false
                // this.errors.push(e)
            })
            
          }
          
        }else{
          // do process upload first before create data.
          uploadFile(data).then(response => {
            if(response.success == true){
              Flash.setSuccess(response.message)
              // create data
              this.formData.image = response.data.image_url
              createData(this.formData).then(response => {
                if(response.success == true){
                  Flash.setSuccess(response.message)
                  this.clearForm()
                  event.target.reset()
                }else{
                  Flash.setError(response.message)
                }
              })
            }else{
              Flash.setError(response.message)
            }
          }).catch(e => {
              Flash.setError("Error while trying to upload file!")
              this.showSendMoneyForm = false
              // this.errors.push(e)
          })         
        }/*#End*/
      }
      
    }
  }
</script>
