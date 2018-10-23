<template>
  <div class="animated fadeIn">
    <div class="col-lg-12">
      <b-alert v-if="flash.success" variant="success" show>{{ flash.success }}</b-alert>
      <b-alert v-if="flash.error" variant="danger" show>{{ flash.error }}</b-alert>
      <b-card header="<i class='fa fa-align-justify'></i> List Data <a href='/#/merchant-account/account-master/form' class='pull-right btn btn-primary btn-sm'><i class='icon-plus'></i> Add</a>">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>Merchant Name</th>
              <th>Merchant ID</th>
              <th>Acc.Expired</th>
              <th>Email</th>
              <th>Active</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="data, key in datatable['data']">
              <td>{{++key}}</td>
              <td>{{data.merchant_name}}</td>
              <td>{{data.merchant_id}}</td>
              <td>{{data.expired_account}}</td>
              <td>{{data.email}}</td>
              <td>
                <span v-if="data.is_active==0" class="badge badge-danger">Inactive</span>
                <span v-else class="badge badge-success">Active</span>
              </td>
              <td>
                <button type="button" @click="viewAccount(data.account_master_id)" class="btn btn-success"><i class="icon-user"></i></button>
                <button type="button" @click="viewEdit(data.account_master_id)" class="btn btn-primary"><i class="icon-note"></i></button>
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
	import {fetchList,deleteData} from '../../../../../api/merchantAccount/merchantAccount'
  import Flash from '../../../../../services/flash'
	import axios from 'axios'
	export default{
		data(){
			return{
        backUrl:'/merchant-account/account-master',
        datatable:[],
        flash: Flash.state
      }
    },
		created(){
      Flash.setLoading(true)
			this.getData();
		},
    methods:{
      getData() {
        fetchList().then(response => {
          this.datatable = response
        })
      },
      viewAccount(id){
        this.$router.push(this.backUrl+'/account/'+id)
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
      }
    }
	}
</script>