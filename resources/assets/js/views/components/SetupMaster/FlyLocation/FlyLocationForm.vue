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
            <b-form-group id="exampleInputGroup3"
                          label="Country *"
                          label-for="exampleInput3">
              <b-form-select id="exampleInput3"
                            :options="country"
                            required
                            v-model="formData.country_id">
              </b-form-select>
            </b-form-group>

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
  import {createData,editData,updateData} from '../../../../../api/setupmaster/flyLocation'
  import {fetchCountryList} from '../../../../../api/setupmaster/country'
  import Flash from '../../../../../services/flash'
  export default {
    props:['id'],
    data(){
      return {
        backUrl:'/fly-location',
        name:'Fly Location Form',
        formData:{
          name:'',
          country_id:null
        },
        flash: Flash.state,
        country:[],
        country: [{ text: 'Select Country', value: null }]
        // country: [
        //   {text:'Select Country', value: null },
        //   {text:'Cambodia',value:36}, 
        //   {text:'South Korea',value:113},
        // ],
      }
    },
    
    created(){
      if(this.id){
        Flash.setLoading(true)
			  this.getData();
      }
      this.getAllCountry()
		},
    methods: {
      clearForm(){
        this.formData.name= ''
        this.formData.country_id= null
      },
      getData() {
        editData(this.id).then(response => {
          // fetch all form data
          this.formData.name = response['data']['name']
          this.formData.country_id = response['data']['country_id']
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
