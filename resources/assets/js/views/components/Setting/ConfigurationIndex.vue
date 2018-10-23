<template>
  <div class="_card">
    <div class="animated fadeIn">
      <div class="col-sm-12">
        <b-alert v-if="flash.success" variant="success" show>{{ flash.success }}</b-alert>
        <b-alert v-if="flash.error" variant="danger" show>{{ flash.error }}</b-alert>
        <form id="form" enctype="multipart/form-data" @submit.prevent="onSubmit">
          <b-card header="<i class='fa fa-align-justify'></i> Configuration <button type='submit' size='sm' class='pull-right btn btn-primary btn-sm'><i class='fa fa-save'></i> Submit</button>">
            <b-tabs v-model="tabIndex">

              <!--Tab General-->
              <b-tab title="<i class='fa fa-home'></i> General">  
                <b-form-fieldset
                  label="Meta Title *"
                  :label-cols="2"
                  :horizontal="true">
                  <b-form-input v-model="formData.config_meta_title" required placeholder="Meta Title" class="col-sm-6" type="text"></b-form-input>
                </b-form-fieldset>

                <b-form-fieldset
                  label="Meta Keyword *"
                  :label-cols="2"
                  :horizontal="true">
                  <b-form-input v-model="formData.config_meta_keyword" required placeholder="Meta Keyword" class="col-sm-6" type="text"></b-form-input>
                </b-form-fieldset>
                
                <b-form-fieldset
                  label="Meta Description *"
                  :label-cols="2"
                  :horizontal="true">
                  <b-form-input v-model="formData.config_meta_description" required placeholder="Meta Description" class="col-sm-6" type="text"></b-form-input>
                </b-form-fieldset>

                <b-form-fieldset
                  label="Language Code *"
                  :label-cols="2"
                  :horizontal="true">
                  <b-form-input v-model="formData.config_language" required placeholder="en" class="col-sm-6" type="text"></b-form-input>
                </b-form-fieldset>

                <b-form-fieldset
                  label="Config Open *"
                  :label-cols="2"
                  :horizontal="true">
                  <b-form-input v-model="formData.config_open" required placeholder="Open Working Hour" class="col-sm-6" type="text"></b-form-input>
                </b-form-fieldset>

              </b-tab>

              <!--Tab Company-->
              <b-tab title="<i class='fa fa-home'></i> Company">  
                <b-form-fieldset>
                  <span v-if="imagePreview.length > 0">
                    <img width="80px" :src="imagePreview"/><br/>
                  </span>
                  <!--<input type="file" id="image" name="image" v-on:change="previewImage" accept="image/*">-->
                  <b-form-file id="image" class="col-sm-6" name="image" v-on:change="previewImage" accept="image/*" placeholder="Choose a file..."></b-form-file>
                </b-form-fieldset>
                <input type="hidden" v-model="formData.config_image"/>
                <b-form-fieldset
                  label="Company Name *"
                  :label-cols="2"
                  :horizontal="true">
                  <b-form-input v-model="formData.config_name" required placeholder="Company Name" class="col-sm-6" type="text"></b-form-input>
                </b-form-fieldset>

                <b-form-fieldset
                  label="Contact *"
                  :label-cols="2"
                  :horizontal="true">
                  <b-form-input v-model="formData.config_contact" required placeholder="Contact" class="col-sm-6" type="text"></b-form-input>
                </b-form-fieldset>

                <b-form-fieldset
                  label="Email *"
                  :label-cols="2"
                  :horizontal="true">
                  <b-form-input v-model="formData.config_email" required placeholder="Email" class="col-sm-6" type="email"></b-form-input>
                </b-form-fieldset>

                <b-form-fieldset
                  label="Fax *"
                  :label-cols="2"
                  :horizontal="true">
                  <b-form-input v-model="formData.config_fax" required placeholder="Fax" class="col-sm-6" type="text"></b-form-input>
                </b-form-fieldset>

                <b-form-fieldset
                  label="Owner *"
                  :label-cols="2"
                  :horizontal="true">
                  <b-form-input v-model="formData.config_owner" required placeholder="Owner" class="col-sm-6" type="text"></b-form-input>
                </b-form-fieldset>

                <b-form-fieldset
                  label="Address *"
                  :label-cols="2"
                  :horizontal="true">
                  <b-form-input v-model="formData.config_address" required placeholder="Owner" class="col-sm-6" type="text"></b-form-input>
                </b-form-fieldset>
  
              </b-tab>

              <!--Tab Local-->
              <b-tab title="<i class='fa fa-cogs'></i> Invoice & Tax">
                <div class="row">
                  <!--col-sm-12-->
                  <div class="col-sm-12">
                    <b-form-fieldset
                      label="Transfer Prefix *"
                      :label-cols="2"
                      :horizontal="true">
                      <b-form-input v-model="formData.config_transfer_prefix" required placeholder="Transfer Prefix" class="col-sm-6" type="text"></b-form-input>
                    </b-form-fieldset>

                    <b-form-fieldset
                      label="Invoice Prefix *"
                      :label-cols="2"
                      :horizontal="true">
                      <b-form-input v-model="formData.config_invoice_prefix" required placeholder="Invoice Prefix" class="col-sm-6" type="text"></b-form-input>
                    </b-form-fieldset>

                    <b-form-fieldset
                      label="Receipt Prefix *"
                      :label-cols="2"
                      :horizontal="true">
                      <b-form-input v-model="formData.config_receipt_prefix" required placeholder="Receipt Prefix" class="col-sm-6" type="text"></b-form-input>
                    </b-form-fieldset>

                    <b-form-fieldset
                      label="URL *"
                      :label-cols="2"
                      :horizontal="true">
                      <b-form-input v-model="formData.config_url" required placeholder="URL" class="col-sm-6" type="text"></b-form-input>
                    </b-form-fieldset>

                    <b-form-fieldset
                      label="Tax *"
                      :label-cols="2"
                      :horizontal="true">
                      <b-form-input v-model="formData.config_tax" required placeholder="Tax" class="col-sm-6" type="text"></b-form-input>
                    </b-form-fieldset>

                    <b-form-fieldset
                      label="Currency *"
                      :label-cols="2"
                      :horizontal="true">
                      <div class="row col-sm-10">
                        <b-form-select class="col-sm-5" id="exampleInput3"
                                      :label-cols="2"
                                      :options="currency"
                                      required
                                      v-model="formData.config_currency">
                        </b-form-select>
                      </div>
                    </b-form-fieldset>

                    <b-form-fieldset
                      label="Currency Code *"
                      :label-cols="2"
                      :horizontal="true">
                      <b-form-input v-model="formData.config_currency_code" required placeholder="Currency Code" class="col-sm-6" type="text"></b-form-input>
                    </b-form-fieldset>

                  </div>
                </div>
              </b-tab>

            </b-tabs>
          </b-card>
        </form>
      </div><!--/.row-->
    </div>
  </div>
</template>


<script>
	import {fetchList,deleteData,createData,editData,updateData} from '../../../../api/setting/setting'
  import {fetchCurrencyList} from '../../../../api/setupmaster/currency'
  import {uploadFile} from '../../../../services/fileUpload'
  import Flash from '../../../../services/flash'
	import axios from 'axios'
	export default{
		data(){
			return{
        backUrl:'/merchant-account/account-rule',
        datatable:[],
        tabIndex:0,
        file:'',
        imagePreview:'',
        formData:{
          config_store_id:0,
          config_language_id:1,
          config_language:'en-gb',
          config_account_id:0,
          config_icon:'/images/store/bakou.png',
          config_image:'/images/store/bakou.png',
          config_logo:'/images/store/bakou.png',
          config_name:'none-value',
          config_contact:'none-value',
          config_email:'none-value',
          config_fax:'none-value',
          config_owner:'none-value',
          config_address:'none-value',
          config_transfer_prefix:'TRN-',
          config_invoice_prefix:'none-value',
          config_receipt_prefix:'none-value',
          config_url:'none-value',
          config_checkout_id:0,
          config_currency:0,
          config_checkout_guest:0,
          config_cart_weight:0,
          config_customer_price:0,
          config_customer_group_id:1,
          config_tax_customer:0,
          config_tax_default:0,
          config_currency_code: 'USD',
          config_mobile_delivery_method:5,
          config_order_status_id:7,
          config_secure:0,
          config_ssl:'none-value',
          config_stock_checkout:0,
          config_stock_display:0,
          config_tax:0,
          config_tax_customer:0,
          config_tax_default:0,
          config_zone_id:855,
          config_country_id:36,
          config_comment:'none-value',
          config_open:'none-value',
          config_geocode:'none-value',
          config_layout_id:0,
          config_theme:'default',
          config_meta_keyword:'none-value',
          config_meta_description:'none-value',
          config_meta_title:'none-value',
          config_review_status:0,
          config_user_group_regsiter:6
        },
        currency:[{text:'Choose Currency', value: 0 }],
        flash: Flash.state
      }
    },
		created(){
      Flash.setLoading(true)
      this.getData()
      this.getAllCurrency()
		},
    methods:{
      clearForm(){
        this.formData= ''
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
                this.formData.config_image = e.target.result;
            }
            // Start the reader job - read file as a data url (base64 format)
            reader.readAsDataURL(input.files[0]);
        }
      },
      getData() {
        fetchList().then(response => {
          this.formData.config_store_id = response['data']['config_store_id']
          this.formData.config_language_id = response['data']['config_language_id']
          this.formData.config_language = response['data']['config_language']
          this.formData.config_account_id = response['data']['config_account_id']
          this.formData.config_icon = response['data']['config_icon']
          this.formData.config_image = response['data']['config_image']
          this.imagePreview = response['data']['config_image']
          this.formData.config_logo = response['data']['config_logo']
          this.formData.config_name = response['data']['config_name']
          this.formData.config_contact = response['data']['config_contact']
          this.formData.config_email = response['data']['config_email']
          this.formData.config_fax = response['data']['config_fax']
          this.formData.config_owner = response['data']['config_owner']
          this.formData.config_address = response['data']['config_address']
          this.formData.config_transfer_prefix = response['data']['config_transfer_prefix']
          this.formData.config_invoice_prefix = response['data']['config_invoice_prefix']
          this.formData.config_receipt_prefix = response['data']['config_receipt_prefix']
          this.formData.config_url = response['data']['config_url']
          this.formData.config_checkout_id = response['data']['config_checkout_id']
          this.formData.config_currency = response['data']['config_currency']
          this.formData.config_checkout_guest = response['data']['config_checkout_guest']
          this.formData.config_cart_weight = response['data']['config_cart_weight']
          this.formData.config_customer_price = response['data']['config_customer_price']
          this.formData.config_customer_group_id = response['data']['config_customer_group_id']
          this.formData.config_tax_customer = response['data']['config_tax_customer']
          this.formData.config_tax_default = response['data']['config_tax_default']
          this.formData.config_currency_code = response['data']['config_currency_code']
          this.formData.config_mobile_delivery_method = response['data']['config_mobile_delivery_method']
          this.formData.config_order_status_id = response['data']['config_order_status_id']
          this.formData.config_secure = response['data']['config_secure']
          this.formData.config_ssl = response['data']['config_ssl']
          this.formData.config_stock_checkout = response['data']['config_stock_checkout']
          this.formData.config_stock_display = response['data']['config_stock_display']
          this.formData.config_tax = response['data']['config_tax']
          this.formData.config_tax_customer = response['data']['config_tax_customer']
          this.formData.config_tax_default = response['data']['config_tax_default']
          this.formData.config_zone_id = response['data']['config_zone_id']
          this.formData.config_country_id = response['data']['config_country_id']
          this.formData.config_comment = response['data']['config_comment']
          this.formData.config_open = response['data']['config_open']
          this.formData.config_geocode = response['data']['config_geocode']
          this.formData.config_layout_id = response['data']['config_layout_id']
          this.formData.config_theme = response['data']['config_theme']
          this.formData.config_meta_keyword = response['data']['config_meta_keyword']
          this.formData.config_meta_description = response['data']['config_meta_description']
          this.formData.config_meta_title = response['data']['config_meta_title']
          this.formData.config_review_status = response['data']['config_review_status']
          this.formData.config_user_group_regsiter = response['data']['config_user_group_regsiter']
        })
      },
      viewEdit(id){
        this.$router.push(this.backUrl+'/'+id)
      },
      initDelete(id){
        var r = confirm("Are you sure to delete?");
        if (r == true) {
          deleteData(id).then(response => {
            if(response.success == true){
              Flash.setSuccess(response.message)
              this.getData();
            }else{
              Flash.setError(response.message)
              Flash.setLoading(true)
            }
          })
        }
      },
      onSubmit (event) {
        // If has props edit data
        createData(this.formData).then(response => {
          if(response.success == true){
            Flash.setSuccess(response.message)
            // event.target.reset()
          }else{
            Flash.setError(response.message)
          }
        })
      }
    }
	}
</script>