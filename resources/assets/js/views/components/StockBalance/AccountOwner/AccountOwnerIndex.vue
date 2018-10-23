<template>
  <div class="animated fadeIn">
    <div class="col-lg-12">
      <b-alert v-if="flash.success" variant="success" show>{{ flash.success }}</b-alert>
      <b-alert v-if="flash.error" variant="danger" show>{{ flash.error }}</b-alert>

      <!--modal account-->
      <!--<modal-account-form
        ref="child"
        :options="{ responsive: true, maintainAspectRatio: false }"
        :account_no="account_no"
        :flag_account="flag_account"
        :formData="formData">
      </modal-account-form>-->

      <b-card header="<i class='fa fa-align-justify'></i> List Data <a href='/#/stocks/account-owner/form' class='pull-right btn btn-primary btn-sm'><i class='icon-plus'></i> Add</a>">
        <table class="table table-striped">
          <thead>
            <thead>
              <tr>
                <th>No</th>
                <th>Bank Account</th>
                <th>Bank Account Name</th>
                <th>Name</th>
                <th>Available Credit</th>
                <th>Currency</th>
                <th>Stock Status</th>
                <th>Is Active?</th>
                <th>Action</th>
              </tr>
            </thead>
          </thead>
          <tbody>
            <tr v-for="data, key in datatable['data']">
              <td>{{++key}}</td>
              <td>{{data.bank_name}}</td>
              <td>{{data.bank_account_name}}</td>
              <td>{{data.name}}</td>
              <td>{{data.available_credit}}</td>
              <td>{{data.currency}}</td>
              <td>{{data.stock_status}}</td>
              <td>{{data.is_active}}</td>
              <td>
                <button type="button" @click="viewAccountDetail(data.account_owner_id)" class="btn btn-success"><i class="icon-user"></i></button>
                <button type="button" @click="viewAccount(data.account_owner_id)" class="btn btn-primary"><i class="icon-eye"></i></button>
              </td>
            </tr>
          </tbody>
        </table>
        <!--<ul class="pagination">
          <li class="page-item"><a class="page-link" href="#">Prev</a></li>
          <li class="page-item active">
            <a class="page-link" href="#">1</a>
          </li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item"><a class="page-link" href="#">4</a></li>
          <li class="page-item"><a class="page-link" href="#">Next</a></li>
        </ul>-->
      </b-card>
    </div><!--/.row-->
  </div>

</template>


<script>
	import {fetchList,fetchAccountDetail,deleteData} from '../../../../../api/stockBalance/accountOwner'
  import Flash from '../../../../../services/flash'
	import axios from 'axios'
  import ModalAccountForm from './Component/ModalAccountForm.vue'

	export default{
    props:['id'],
		data(){
			return{
        backUrl:'/stocks/account-owner',
        datatable:[],
        flash: Flash.state,
        showAccountList:false,
      }
    },
		created(){
      Flash.setLoading(true)
			this.getData();
		},
    components:{
      ModalAccountForm
    },
    methods:{
      getData() {
        fetchList().then(response => {
          this.datatable = response
        })
      },
      viewAccount(id){
        this.$router.push(this.backUrl+'/'+id)
      },
      viewAccountDetail(account_owner_id){
        this.$router.push(this.backUrl+'/detail/'+account_owner_id)
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
      }
    }
	}
</script>