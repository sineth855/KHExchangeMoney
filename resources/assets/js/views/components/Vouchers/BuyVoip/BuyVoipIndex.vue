<template>
  <div class="animated fadeIn">
    <div class="col-lg-12">
      <b-alert v-if="flash.success" variant="success" show>{{ flash.success }}</b-alert>
      <b-alert v-if="flash.error" variant="danger" show>{{ flash.error }}</b-alert>
      <b-card header="<i class='fa fa-align-justify'></i> List Data <a href='/#/buy-voip/form' class='pull-right btn btn-primary btn-sm'><i class='icon-plus'></i> New Purchase</a>">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Account No</th>
              <th>Voip Account</th>
              <th>Amount </th>
              <th>Currency </th>
              <th>Date Added </th>
              <th>Status </th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="data, key in datatable['data']">
              <td>{{++key}}</td>
              <td>{{data.account_no}}</td>
              <td>{{data.voip_account_no}}</td>
              <td>{{data.amount}}</td>
              <td>{{data.currency}}</td>
              <td>{{data.date_added}}</td>
              <td>
                <span v-if="data.status==0" class="badge badge-primary">Proccessing</span>
                <span v-else-if="data.status==1" class="badge badge-success">Completed</span>
                <span v-else class="badge badge-danger">Voided</span>
              </td>
              <td>
                <b-form-fieldset>
                    <b-input-group>
                        <!-- Attach Left button -->
                        <b-input-group-button class="btn-xs" slot="left">
                            <b-dropdown text="Action" variant="primary">
                              <b-dropdown-item @click="viewEdit(data.buy_voip_id)"><i class="icon-pencil"></i> View</b-dropdown-item>
                              <!--<b-dropdown-item><i class="icon-eye"></i>Void</b-dropdown-item>-->
                              <b-dropdown-item v-if="data.status!=2" @click="initAction(data.buy_voip_id,1,data.account_no,data.product_id,data.quantity)"><i class="label label-success icon-check"></i> Approved</b-dropdown-item>
                              <b-dropdown-item v-if="data.status!=1" @click="initAction(data.buy_voip_id,2,data.account_no,data.product_id,data.quantity)"><i class="icon-action-undo"></i> Void</b-dropdown-item>
                            </b-dropdown>
                        </b-input-group-button>
                    </b-input-group>
                </b-form-fieldset>
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
	import {fetchList,deleteData,updateData} from '../../../../../api/voip/buyVoip'
  import Flash from '../../../../../services/flash'
	import axios from 'axios'
	export default{
		data(){
			return{
        backUrl:'/buy-voip',
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
      },
      initAction(buy_voip_id,status,account_no,product_id,quantity){
        var result = confirm("Are you sure to convert?");
        if (result) {
          const data = {
                        status:status,
                        account_no:account_no,
                        product_id:product_id,
                        quantity:quantity
                       }
          updateData(data,buy_voip_id).then(response => {
            if(response.success == true){
              Flash.setSuccess(response.message)
            }else{
              Flash.setError(response.message)
            }
          })
          this.getData()
        }else{
          this.getData()
        }
      }

    }
	}
</script>