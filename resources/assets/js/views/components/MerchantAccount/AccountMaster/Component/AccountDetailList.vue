<template>
    <div class="card-block">
        <div>
            <b-alert v-if="flash.success" variant="success" show>{{ flash.success }}</b-alert>
            <b-alert v-if="flash.error" variant="danger" show>{{ flash.error }}</b-alert>
            <!--modal view detail account-->
            <b-tabs v-model="tabIndex">
                <!--Tab Credit-->
                <b-tab title="<i class='fa fa-money'></i> Account Credit">
                    <!--modal credit form-->
                    <modal-credit-form 
                        ref="child"
                        :account_id="account_id"
                        :flag="flag" 
                        v-on:event_child="eventChild"> 
                    </modal-credit-form>

                    <div class="row">
                        <!--col-sm-8-->
                        <div class="col-sm-8">
                        <!--<account-credit-form :showAction="showFormCredit"></account-credit-form>-->
                        </div>
                    </div>

                    <div>
                        <div class="pull-right"><a @click="viewFormCredit(1,'')" href="javascript:void(0)"><i class="icon-plus"></i> New Credit</a></div>
                        <table class="table table-striped">
                        <thead>
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Credit Amount</th>
                                <th>Credit Amount History</th>
                                <th>Deposit Date</th>
                                <th>Is Active?</th>
                                <th>Status</th>
                                <th>Deposit By</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </thead>
                        <tbody>
                            <tr v-if="accountData['account_credit'].length>0" v-for="data, key in accountData['account_credit']">
                            <td>{{++key}}</td>
                            <td>{{data.credit_amount}}</td>
                            <td>{{data.credit_amount_history}}</td>
                            <td>{{data.deposit_date}}</td>
                            <td>
                                <span v-if="data.is_active==1" class="badge badge-success">Active</span>
                                <span v-else class="badge badge-danger">Deactive</span>
                            </td>
                            <td>
                                <span v-if="data.status==1" class="badge badge-success">Transfered</span>
                            </td>
                            <td>{{data.deposit_by}}</td>
                            <td>
                                <button type="button" @click="viewFormCredit(2,data)" class="btn btn-success"><i class="icon-eye"></i></button>
                                <!--<button v-if="data.status==1" type="button" @click="viewAccount(data.account_id,2)" class="btn btn-primary"><i class="icon-note"></i></button>-->
                            </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                </b-tab>
                <!--Tab Send Money-->
                <b-tab title="<i class='icon-arrow-down-circle'></i> Send Money">
                    <!--modal send money form-->
                    <modal-send-money-form 
                        ref="childSendMoney"
                        :account_id="account_id"
                        :accountCurrencyData="accountCurrencyData"
                        :account_master_id="account_master_id"
                        :flag="flag" 
                        v-on:event_child="eventChild"> 
                    </modal-send-money-form>
                <div class="pull-right"><a href="javascript:void(0)" @click="viewFormSendMoney(1,'')"><i class="icon-plus"></i> New Send Money</a></div>
                <table class="table table-striped">
                    <thead>
                    <thead>
                        <tr>
                        <th>No</th>
                        <th>Sending Amount</th>
                        <th>Send Currency</th>
                        <th>Charged Fee</th>
                        <th>Exchange Rate</th>
                        <th>Receiving Amount</th>
                        <th>Receiving Contact</th>
                        <th>Receiving Name</th>
                        <th>Receiving Date</th>
                        <th>Is Received?</th>
                        <th>Status</th>
                        <th>Action</th>
                        </tr>
                    </thead>
                    </thead>
                    <tbody>
                    <tr v-if="accountData['account_send_money'].length>0" v-for="data, key in accountData['account_send_money']">
                        <td>{{++key}}</td>
                        <td>{{data.sending_amount}}</td>
                        <td>{{data.sending_currency}}</td>
                        <th>{{data.charge_fee}}</th>
                        <td>{{data.exchange_rate}}</td>
                        <td>{{data.receiving_amount}} {{data.receiving_currency}}</td>
                        <td>{{data.receiving_contact}}</td>
                        <td>{{data.receiving_name}}</td>
                        <td>{{data.receiving_date}}</td>
                        <td>
                            <span v-if="data.is_received==1" class="badge badge-success">Received</span>
                            <span v-else class="badge badge-danger">Not Received</span>
                        </td>
                        <td>
                            <span v-if="data.status==1" class="badge badge-success">Processed</span>
                            <span v-else class="badge badge-warning">Pending</span>
                        </td>
                        <td>
                            <button type="button" @click="viewFormSendMoney(2,data)" class="btn btn-success"><i class="icon-eye"></i></button>
                            <!--<button type="button" @click="viewAccount(data.account_id,2)" class="btn btn-primary"><i class="icon-note"></i></button>-->
                        </td>
                    </tr>
                    </tbody>
                </table>
                </b-tab>
                <!--Tab Transfer Money-->
                <b-tab title="<i class='icon-arrow-up-circle'></i> Transfer Money">
                    <!--modal transfer money form-->
                    <modal-transfer-form 
                        ref="childTransfer"
                        :account_id="account_id"
                        :accountCurrencyData="accountCurrencyData"
                        :account_master_id="account_master_id"
                        :flag="flag" 
                        v-on:event_child="eventChild"> 
                    </modal-transfer-form>
                <div class="pull-right"><a href="javascript:void(0)" @click="viewFormTransfer(1,'')"><i class="icon-plus"></i> New Transfer</a></div>
                <table class="table table-striped">
                    <thead>
                    <thead>
                        <tr>
                        <th>No</th>
                        <th>Transfer No</th>
                        <th>Transfer From</th>
                        <th>Transfer To</th>
                        <th>Transfer Amount</th>
                        <th>Transfer Date</th>
                        <th>Transfer Fee</th>
                        <th>Status</th>
                        <th>Action</th>
                        </tr>
                    </thead>
                    </thead>
                    <tbody>
                    <tr v-if="accountData['account_transfer'].length>0" v-for="data, key in accountData['account_transfer']">
                        <td>{{++key}}</td>
                        <td>{{data.transfer_no}}</td>
                        <td>{{data.transfer_from_account_no}}</td>
                        <td>{{data.transfer_to_account_no}}</td>
                        <td>{{data.transfer_amount}}</td>
                        <td>{{data.transfer_date}}</td>
                        <td>{{data.transfer_fee}}</td>
                        <td>
                            <span v-if="data.status==1" class="badge badge-success">Transfered</span>
                            <span v-else class="badge badge-warning">Pending</span>
                        </td>
                        <td>
                        <button type="button" @click="viewFormTransfer(2,data)" class="btn btn-success"><i class="icon-eye"></i></button>
                        <!--<button type="button" @click="viewAccount(data.account_id,2)" class="btn btn-primary"><i class="icon-note"></i></button>-->
                        </td>
                    </tr>
                    </tbody>
                </table>
                </b-tab>
                <!--Tab Account Loan-->
                <b-tab title="<i class='icon-bag'></i> Account Loan">
                    <!--modal Loan form-->
                    <modal-loan-form 
                        ref="childLoan"
                        :account_id="account_id"
                        :accountCurrencyData="accountCurrencyData"
                        :account_master_id="account_master_id"
                        :flag="flag" 
                        v-on:event_child="eventChild"> 
                    </modal-loan-form>
                    <!--modal Loan Payment List-->
                    <modal-loan-payment 
                        ref="childLoanPayment"
                        :account_id="account_id"
                        :account_master_id="account_master_id"
                        @emitEvent="emitEvent()"
                        v-on:event_child="eventChild"> 
                    </modal-loan-payment>
                <div class="pull-right"><a href="javascript:void(0)" @click="viewFormLoan(1,'')"><i class="icon-plus"></i> New Loan</a></div>
                <table class="table table-striped">
                    <thead>
                    <thead>
                        <tr>
                        <th>No</th>
                        <th>Loan Product</th>
                        <th>Loan Amount</th>
                        <th>Balance</th>
                        <th>Tax</th>
                        <th>Currency</th>
                        <th>Loan Duration</th>
                        <th>Exchange Rate</th>
                        <th>Request Date</th>
                        <th>Is Approved?</th>
                        <th>Status</th>
                        <th>Action</th>
                        </tr>
                    </thead>
                    </thead>
                    <tbody>
                    <tr v-if="accountData['account_loan'].length>0" v-for="data, key in accountData['account_loan']">
                        <td>{{++key}}</td>
                        <td>{{data.loan_product}}</td>
                        <td>{{data.principle_amount}}</td>
                        <td>{{data.total_balance}}</td>
                        <td>{{data.tax_amount}}</td>
                        <td>{{data.currency}}</td>
                        <td>{{data.loan_duration}} {{data.term_day}}</td>
                        <td>{{data.exchange_rate}}</td>
                        <td>{{data.request_date}}</td>
                        <td>
                            <span v-if="data.is_approved==0" class="badge badge-danger">Not Approved</span>
                            <span v-else class="badge badge-success">Approved</span>
                        </td>
                        <td>
                            <!--Status = 0(Owned) , status = 1(Refund), status = 2(Void)*/-->
                            <span v-if="data.status==0" class="badge badge-danger">Owed</span>
                            <span v-else-if="data.status==1" class="badge badge-success">Refund</span>
                            <span v-else class="badge badge-warning">Void</span>
                        </td>
                        <td>
                            <!--<button type="button" @click="viewFormLoan(2,data)" class="btn btn-success"><i class="icon-eye"></i></button>-->
                            <b-form-fieldset>
                                <b-input-group>
                                    <!-- Attach Left button -->
                                    <b-input-group-button class="btn-xs" slot="left">
                                        <b-dropdown text="Action" variant="primary">
                                            <b-dropdown-item @click="viewFormLoan(2,data)"><i class="icon-eye"></i>View</b-dropdown-item>
                                            <!--<b-dropdown-item><i class="icon-eye"></i>Void</b-dropdown-item>-->
                                            <b-dropdown-item @click="viewLoanPayment(data)"><i class="icon-wallet"></i>Loan Payment</b-dropdown-item>
                                        </b-dropdown>
                                    </b-input-group-button>
                                </b-input-group>
                            </b-form-fieldset>
                        </td>
                    </tr>
                    </tbody>
                </table>
                </b-tab>
                <!--Tab Withdrawal-->
                <b-tab title="<i class='fa fa-money'></i> Withdrawal">
                    <!--modal withdraw form-->
                    <modal-withdraw-form 
                        ref="childWithdraw"
                        :account_id="account_id"
                        :accountCurrencyData="accountCurrencyData"
                        :account_master_id="account_master_id"
                        :flag="flag" 
                        v-on:event_child="eventChild"> 
                    </modal-withdraw-form>
                <div class="pull-right"><a href="javascript:void(0)" @click="viewFormWithdraw(1,'')"><i class="icon-plus"></i> New Withdraw</a></div>
                <table class="table table-striped">
                    <thead>
                    <thead>
                        <tr>
                        <th>No</th>
                        <th>Withdraw Amount</th>
                        <th>Currency</th>
                        <th>Exchange Rate</th>
                        <th>Requested Date</th>
                        <th>Approved Date</th>
                        <th>Status</th>
                        <th>Remark</th>
                        <th>Action</th>
                        </tr>
                    </thead>
                    </thead>
                    <tbody>
                    <tr v-if="accountData['account_withdraw'].length>0" v-for="data, key in accountData['account_withdraw']">
                        <td>{{++key}}</td>
                        <td>{{data.request_amount}}</td>
                        <td>{{data.currency}}</td>
                        <td>{{data.exchange_rate}}</td>
                        <td>{{data.requested_date}}</td>
                        <td>{{data.approved_date}}</td>
                        <td>{{data.status}}</td>
                        <td>{{data.remark}}</td>
                        <td>
                        <button type="button" @click="viewFormWithdraw(2,data)" class="btn btn-success"><i class="icon-eye"></i></button>
                        <!--<button type="button" @click="viewAccount(data.account_id,2)" class="btn btn-primary"><i class="icon-note"></i></button>-->
                        </td>
                    </tr>
                    </tbody>
                </table>
                </b-tab>
                <!--Tab Transaction History-->
                <b-tab title="<i class='icon-graph'></i> Transaction History">
                <table class="table table-striped">
                    <thead>
                    <thead>
                        <tr>
                        <th>No</th>
                        <th>Trans.No</th>
                        <th>Account No</th>
                        <th>Trans.Type</th>
                        <th>Trans.Rule</th>
                        <th>Trans.Amount</th>
                        <th>Currency</th>                        
                        <th>Trans.Date</th>
                        <th>Trans.Detail</th>
                        <th>Action</th>
                        </tr>
                    </thead>
                    </thead>
                    <tbody>
                    <tr v-if="accountData['account_transaction'].length>0" v-for="data, key in accountData['account_transaction']">
                        <td>{{++key}}</td>
                        <td>{{data.transaction_no}}</td>
                        <td>{{data.account_no}}</td>
                        <td>{{data.transaction_type}}</td>
                        <td>{{data.transaction_rule}}</td>
                        <td>{{data.req_amount}}</td>
                        <td>{{data.currency}}</td>
                        <td>{{data.transaction_date}}</td>
                        <td>{{data.transaction_detail}}</td>
                        <td>
                            <button type="button" @click="viewAccountDetail(data.account_id)" class="btn btn-primary"><i class="icon-eye"></i></button>
                            <!--<button type="button" @click="viewAccount(data.account_id,2)" class="btn btn-primary"><i class="icon-note"></i></button>-->
                        </td>
                    </tr>
                    </tbody>
                </table>
                </b-tab>
            </b-tabs>
            <div slot="modal-footer" class="w-100">
                <span class="pull-right">
                <!--<b-button type="reset" variant="default"><i class="fa fa-ban"></i> Cancel</b-button>-->
                <!--<b-button type="submit" variant="primary">Submit sd</b-button>-->
                </span>
            </div>
        <!--</b-modal>-->
        </div>
        <!--New Credit-->
        <!--<b-modal :title="'Form Credit'" class="modal-primary" size="md" v-model="NewFormCredit" >
            New form Credit
            <div slot="modal-footer" class="w-100">
                <span class="pull-right">
                <b-button type="button" @click="modal_close()" variant="default"><i class="fa fa-ban"></i> Cancel</b-button>
                <b-button type="submit" variant="primary">Submit</b-button>
                </span>
            </div>
        </b-modal>-->
    </div>
</template>

<script>
    import ModalCreditForm from './ModalCreditForm.vue'
    import ModalSendMoneyForm from './ModalSendMoneyForm.vue'
    import ModalTransferForm from './ModalTransferForm.vue'
    import ModalLoanForm from './ModalLoanForm.vue'
    import ModalLoanPayment from './_ModalLoanPayment.vue'
    import ModalWithdrawForm from './ModalWithdrawForm.vue'
    import Flash from '../../../../../../services/flash'
    export default{
        props:[
                'accountData',
                'showAccountList',
                'accountCurrencyData',
                'account_id',
                'account_master_id'
            ],
        data(){
            return{
                tabIndex:0,
                flag:1,
                flash: Flash.state,
                modalAccountDetail:false,
                account_credit_id:0
            }
        },
        components:{
            ModalCreditForm,
            ModalSendMoneyForm,
            ModalTransferForm,
            ModalLoanForm,
            ModalLoanPayment,
            ModalWithdrawForm
        },
        created() {
            
        },
        methods:{
            //******* call back event from current component with alternative component*******
            // Modal Account Credit Form
            eventChild: function(data) {
                // alert("testng")
                this.$emit('event_child', this.account_id)
            },
            /*eventParent: function() {
                this.$emit('event_parent', 1)
            },*/
            // Modal Account Send Money Form
            /*eventChild: function(account_id) {
                this.$emit('event_child', account_id)
            },*/
            ChangeView(){
                alert("welcome back repl")
            },
            viewFormCredit(flag,data){
                this.flag = flag
                this.$refs.child.child_method(flag,data)
            },
            viewFormSendMoney(flag,data){
                this.flag = flag
                this.$refs.childSendMoney.child_method(flag,data)
            },
            viewFormTransfer(flag,data){
                this.flag = flag
                this.$refs.childTransfer.child_method(flag,data)
            },
            viewFormLoan(flag,data){
                this.flag = flag
                this.$refs.childLoan.child_method(flag,data)
            },
            viewLoanPayment(data){
                this.$refs.childLoanPayment.child_method(data)
            },
            viewFormWithdraw(flag,data){
                this.flag = flag
                this.$refs.childWithdraw.child_method(flag,data)
            }
        }
    }
</script>