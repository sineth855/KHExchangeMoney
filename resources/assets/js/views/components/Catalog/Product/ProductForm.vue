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
                        label="Category *"
                        label-for="exampleInput3">
              <b-form-select id="exampleInput3"
                              :options="category"
                              required
                              v-model="formData.category_id">
              </b-form-select>
            </b-form-group>

            <b-form-fieldset label="Quantity *">
              <b-form-input type="number" required v-model="formData.quantity" placeholder="Quantity"></b-form-input>
            </b-form-fieldset>

            <b-form-fieldset label="Model">
              <b-form-input type="text" v-model="formData.model" placeholder="Model"></b-form-input>
            </b-form-fieldset>

            <b-form-fieldset label="Price *">
              <b-form-input type="text" required v-model="formData.price" placeholder="Price"></b-form-input>
            </b-form-fieldset>

            <b-form-fieldset label="Cost *">
              <b-form-input type="text" required v-model="formData.cost" placeholder="Cost"></b-form-input>
            </b-form-fieldset>

            <b-form-fieldset label="Manufacturer">
              <b-form-input type="text" v-model="formData.manufacturer" placeholder="Manufacturer"></b-form-input>
            </b-form-fieldset>

            <b-form-group id="exampleInputGroup3"
                        label="Stock Status *"
                        label-for="exampleInput3">
              <b-form-select id="exampleInput3"
                              :options="stockStatus"
                              required
                              v-model="formData.stock_status_id">
              </b-form-select>
            </b-form-group>
            
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
  import {fetchList} from '../../../../../api/catalog/category'
  import {fetchStockStatusList} from '../../../../../api/setupmaster/stockStatus'
  import {createData,editData,updateData} from '../../../../../api/catalog/product'
  import Flash from '../../../../../services/flash'
  export default {
    props:['id'],
    data(){
      return {
        backUrl:'/product',
        name:'Product Form',
        formData:{
          name:'',
          category_id:null,
          stock_status_id:null,
          quantity:'',
          manufacturer:'',
          model:'',
          cost:'',
          price:'',
        },
        stockStatus: [{ text: 'Select Stock Status', value: null }],
        category: [{ text: 'Choose Category', value: null }],
        flash: Flash.state
      }
    },
    
    created(){
      if(this.id){
        Flash.setLoading(true)
			  this.getData();
      }
      this.getAllStockStatus()
      this.getAllCategory()
		},
    methods: {
      getAllCategory(){
        fetchList().then(response => {
            // this.datatable = response
            for(let x=0 ; x <= response['data'].length-1 ; x++){
                let data = {'text':response['data'][x]['name'],'value':response['data'][x]['category_id']}
                this.category.push(data)
            }
        })
      },
      getAllStockStatus(){
        fetchStockStatusList().then(response => {
            // this.datatable = response
            for(let x=0 ; x <= response['data'].length-1 ; x++){
                let data = {'text':response['data'][x]['name'],'value':response['data'][x]['stock_status_id']}
                this.stockStatus.push(data)
            }
        })
      },
      clearForm(){
        this.formData = ''
      },
      getData() {
        editData(this.id).then(response => {
          // fetch all form data
          this.formData.name = response['data']['name']
          this.formData.cost = response['data']['cost']
          this.formData.price = response['data']['price']
          this.formData.model = response['data']['model']
          this.formData.manufacturer = response['data']['manufacturer']
          this.formData.category_id = response['data']['category_id']
          this.formData.quantity = response['data']['quantity']
          this.formData.stock_status_id = response['data']['stock_status_id']
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
