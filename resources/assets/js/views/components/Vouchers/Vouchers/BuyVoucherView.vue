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
                              label="Master Account *"
                              label-for="exampleInput3">
                  <b-form-select id="exampleInput3"
                                :options="accountMaster"
                                required
                                v-model="account_master">
                  </b-form-select>
                </b-form-group>
              </div>
              <div class="col-sm-6">
                <b-form-group id="exampleInputGroup3"
                              label="Account *"
                              label-for="exampleInput3">
                  <b-form-select id="exampleInput3"
                                :options="account"
                                required
                                v-model="formData.from_account_no">
                  </b-form-select>
                </b-form-group>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-6">
                <b-form-group id="exampleInputGroup3"
                              label="Category *"
                              label-for="exampleInput3">
                  <b-form-select id="exampleInput3"
                                :options="category"
                                required
                                v-model="category_id">
                  </b-form-select>
                </b-form-group>
              </div>
              <div class="col-sm-6">
                <b-form-group id="exampleInputGroup3"
                              label="Product *"
                              label-for="exampleInput3">
                  <b-form-select id="exampleInput3"
                                :options="product"
                                required
                                v-model="formData.product_id">
                  </b-form-select>
                </b-form-group>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-6">
                <b-form-group id="exampleInputGroup3"
                              label="Country *"
                              label-for="exampleInput3">
                  <b-form-select id="exampleInput3"
                                :options="country"
                                required
                                v-model="country_id">
                  </b-form-select>
                </b-form-group>
              </div>
              
              <div class="col-sm-6">
                <b-form-fieldset label="Quantity *">
                  <b-form-input type="number" required v-model="formData.quantity" placeholder="Quantity"></b-form-input>
                </b-form-fieldset>
              </div>

              <div class="col-sm-6">
                <b-form-group id="exampleInputGroup3"
                              label="Vouchers *"
                              label-for="exampleInput3">
                  <b-form-select id="exampleInput3"
                                :options="vouchers"
                                required
                                v-model="formData.pay_to_voucher">
                  </b-form-select>
                </b-form-group>
              </div>

              <div class="col-sm-6">
                <b-form-fieldset label="Telephone *">
                  <b-form-input type="text" required v-model="formData.telephone" placeholder="Telephone"></b-form-input>
                </b-form-fieldset>
              </div>

            </div>

            <div _slot="footer">
              <b-button type="reset" @click="onCancel()" size="sm" variant="default"><i class="fa fa-ban"></i> Cancel</b-button>
              <!--<b-button type="submit" size="sm" variant="primary"><i class="fa fa-dot-circle-o"></i> Submit</b-button>-->
            </div>
          </form>
        </b-card>
      </div><!--/.col-->
    </div><!--/.row-->
  </div>
</template>

<script>
  import {fetchCategoryList} from '../../../../../api/catalog/category'
  import {fetchProductBaseCategory} from '../../../../../api/catalog/product'
  import {fetchList,fetchAccount} from '../../../../../api/merchantAccount/merchantAccount'
  import {createData,showData,editData,updateData} from '../../../../../api/vouchers/buyVoucher'
  import {fetchCountryList} from '../../../../../api/setupmaster/country'
  import {fetchVoucherBaseCountry} from '../../../../../api/vouchers/voucher'
  import Flash from '../../../../../services/flash'
  export default {
    props:['id'],
    data(){
      return {
        backUrl:'/buy-voucher',
        name:'Buy Voucher Form',
        country_id:null,
        account_master:null,
        category_id:null,
        formData:{
          from_account_no:null,
          pay_to_voucher:null,
          amount_top_up:'',
          telephone:'',
          transfer_date:'',
          quantity:0,
          category_id:null,
          product_id:null
        },
        flash: Flash.state,
        accountMaster: [
          {text:'Select Master Account', value: null}
        ],
        account: [
          {text:'Select Account', value: null}
        ],
        country: [
          {text:'Select Country', value: null }
        ],
        vouchers: [
          {text:'Select Vouchers', value: null }
        ],
        category: [
          {text:'Choose Category', value: null }
        ],
        product: [
          {text:'Select Product', value: null }
        ],
      }
    },
    watch: {
      category_id: function(category_id){
        Flash.setLoading(true)
        this.product = [
            {text:'--Choose Product--', value: null }
        ]
        this.getProductBaseCategory(category_id)
      },
      country_id: function(country_id){
        Flash.setLoading(true)
        this.vouchers = [
            {text:'Select Vouchers', value: null }
        ]
        this.getAllVoucherBaseCountry(country_id)
      },
      account_master: function(account_master_id){
        Flash.setLoading(true)
        this.account = [
          {text:'Select Account', value: null}
        ]
        this.fetchAccountBaseAccountMaster(account_master_id)
      }
    },
    created(){
      if(this.id){
        Flash.setLoading(true)
			  this.getData();
      }
      this.getAllCountry()
      this.getAllMerchantAccount()
      this.getAllCategories()
		},
    methods: {
      getAllCategories(){
        fetchCategoryList().then(response => {
          // this.datatable = response
          for(let x=0 ; x <= response['data'].length-1 ; x++){
            let data = {'text':response['data'][x]['name'],'value':response['data'][x]['category_id']}
            this.category.push(data)
          }
        })
      },
      getProductBaseCategory(category_id){
        fetchProductBaseCategory(category_id).then(response => {
          // this.datatable = response
          for(let x=0 ; x <= response['data'].length-1 ; x++){
            let data = {'text':response['data'][x]['name'],'value':response['data'][x]['product_id']}
            this.product.push(data)
          }
        })
      },
      getAllMerchantAccount(){
        fetchList().then(response => {
          // this.datatable = response
          for(let x=0 ; x <= response['data'].length-1 ; x++){
            let data = {'text':response['data'][x]['merchant_name']+' ('+response['data'][x]['merchant_id']+')','value':response['data'][x]['account_master_id']}
            this.accountMaster.push(data)
          }
        })
      },
      fetchAccountBaseAccountMaster(account_master_id){
        fetchAccount(account_master_id).then(response => {
          // this.datatable = response
          for(let x=0 ; x <= response['data'].length-1 ; x++){
            let data = {'text':response['data'][x]['account_no'] + ' ('+response['data'][x]['currency']+')','value':response['data'][x]['account_no']}
            this.account.push(data)
          }
          Flash.setLoading(false)
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
      getAllVoucherBaseCountry(country_id) {
        fetchVoucherBaseCountry(country_id).then(response => {
          // this.datatable = response
          for(let x=0 ; x <= response['data'].length-1 ; x++){
            let data = {'text':response['data'][x]['name'],'value':response['data'][x]['name']}
            this.vouchers.push(data)
          }
          Flash.setLoading(false)
        })
      },
      clearForm(){
        this.formData = ''
      },
      getData() {
        showData(this.id).then(response => {
          // fetch all form data
          this.account_master = response['data']['account_master_id']
          this.formData.from_account_no = response['data']['from_account_no']
          this.formData.country_id = response['data']['country_id']
          this.formData.product_id = response['data']['product_id']
          this.category_id = response['data']['category_id']
          this.formData.pay_to_voucher = response['data']['pay_to_voucher']
          this.formData.telephone = response['data']['telephone']
          this.country_id = response['data']['country_id']
          this.formData.telephone = response['data']['telephone']
          this.formData.transfer_date = response['data']['transfer_date']
          this.formData.quantity = response['data']['quantity']
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
