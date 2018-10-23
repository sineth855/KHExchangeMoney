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
                        label="Parent"
                        label-for="exampleInput3">
              <b-form-select id="exampleInput3"
                              :options="category"
                              v-model="formData.parent_id">
              </b-form-select>
            </b-form-group>

            <b-form-fieldset label="Order Level *">
              <b-form-input type="number" required v-model="formData.order_level" placeholder="Order Level"></b-form-input>
            </b-form-fieldset>

            <b-form-fieldset>
                <b-form-checkbox v-model="formData.status"> Status</b-form-checkbox>
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
  import {fetchList,createData,editData,updateData} from '../../../../../api/catalog/category'
  import Flash from '../../../../../services/flash'
  export default {
    props:['id'],
    data(){
      return {
        backUrl:'/category',
        name:'Category Form',
        formData:{
          name:'',
          parent_id:0,
          order_level:0,
          status:1
        },
        category: [{ text: 'Choose Category', value: 0 }],
        flash: Flash.state
      }
    },
    
    created(){
      if(this.id){
        Flash.setLoading(true)
			  this.getData();
      }
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
      clearForm(){
        this.formData = ''
      },
      getData() {
        editData(this.id).then(response => {
          // fetch all form data
          this.formData.name = response['data']['name']
          this.formData.parent_id = response['data']['parent_id']
          this.formData.order_level = response['data']['order_level']
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
