<template>
  <div class="animated fadeIn">
    <div class="col-lg-12">
      <b-alert v-if="flash.success" variant="success" show>{{ flash.success }}</b-alert>
      <b-alert v-if="flash.error" variant="danger" show>{{ flash.error }}</b-alert>
      <b-card header="<i class='fa fa-align-justify'></i> List Data <a href='/#/merchant-account/account-master' class='pull-right btn btn-primary btn-sm'><i class='icon-plus'></i> New Transfer</a>">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Tran.No</th>
              <th>Tran.Amount</th>
              <th>From Currency</th>
              <th>To Currency</th>
              <th>Tran.From</th>
              <th>Tran.To</th>
              <th>Method</th>
              <th>Status</th>
              <!--<th>Action</th>-->
            </tr>
          </thead>
          <tbody>
            <tr v-for="data, key in datatable['data']">
              <td>{{++key}}</td>
              <td>{{data.transfer_no}}</td>
              <td>{{data.transfer_amount}}</td>
              <td>{{data.transfer_from_currency}} </td>
              <td>{{data.transfer_to_currency}} </td>
              <td>{{data.transfer_from_account_no}}</td>
              <td>{{data.transfer_to_account_no}}</td>
              <td>{{data.delivery_method}}</td>
              <td>{{data.status}}</td>
              <!--<td>
                <button type="button" @click="viewEdit(data.account_transfer_id)" class="btn btn-primary"><i class="icon-note"></i> Edit</button>
                <button type="button" @click="initDelete(data.account_transfer_id)" class="btn btn-danger"><i class="icon-trash"></i> Delete</button>
              </td>-->
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
	import {fetchList,deleteData} from '../../../../../api/money/transferMoney'
  import Flash from '../../../../../services/flash'
	import axios from 'axios'
	export default{
		data(){
			return{
        backUrl:'/transfer-money',
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