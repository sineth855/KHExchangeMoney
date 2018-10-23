<template>
  <div class="animated fadeIn">
    <div class="col-lg-12">
      <b-alert v-if="flash.success" variant="success" show>{{ flash.success }}</b-alert>
      <b-alert v-if="flash.error" variant="danger" show>{{ flash.error }}</b-alert>
      <b-card header="<i class='fa fa-align-justify'></i> List Data <a href='/#/stocks/bank-account/form' class='pull-right btn btn-primary btn-sm'><i class='icon-plus'></i> Add</a>">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Merchant ID</th>
              <th>Merchant Name</th>
              <th>Bank Name</th>
              <th>Account No</th>
              <th>Account Name</th>
              <th>Expired Account</th>
              <th>Contact</th>
              <th>Email</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="data, key in datatable['data']">
              <td>{{++key}}</td>
              <td>{{data.merchant_id}}</td>
              <td>{{data.merchant_name}}</td>
              <td>{{data.bank_name}}</td>
              <td>{{data.account_no}}</td>
              <td>{{data.account_name}}</td>
              <td>{{data.expired_account}}</td>
              <td>{{data.contact}}</td>
              <td>{{data.email}}</td>
              <td>
                <button type="button" @click="viewEdit(data.bank_account_id)" class="btn btn-primary"><i class="icon-note"></i> Edit</button>
                <button type="button" @click="initDelete(data.bank_account_id)" class="btn btn-danger"><i class="icon-trash"></i> Delete</button>
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
	import {fetchList,deleteData} from '../../../../../api/stockBalance/bankAccount'
  import Flash from '../../../../../services/flash'
	import axios from 'axios'
	export default{
		data(){
			return{
        backUrl:'/stocks/bank-account',
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