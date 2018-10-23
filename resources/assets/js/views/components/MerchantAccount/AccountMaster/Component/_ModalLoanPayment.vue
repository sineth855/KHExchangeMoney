<template>
    <div>
        <b-form hide-footer>
            <b-modal centered :title="'Loan Payment List'" ok-only size="lg" class="modal-primary" v-model="showLoanPayment" @ok="showLoanPayment = false">
                <b-alert v-if="flash.success" variant="success" show>{{ flash.success }}</b-alert>
                <b-alert v-if="flash.error" variant="danger" show>{{ flash.error }}</b-alert>
                <div>
                    <b-card header="<i class='fa fa-align-justify'></i> Loan Payment">
                        <div class="pull-right"><a href="javascript:void(0)" @click="viewLoanPaymentForm(1,'','')"><i class="icon-plus"></i> New Payment</a></div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Receipt No</th>
                                    <th>Payment Date</th>
                                    <th>Extra Payment</th>
                                    <th>Currency</th>
                                    <th>Tax Amount</th>
                                    <th>Amount</th>
                                    <th>Total Amount</th>
                                    <th>Remark</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="data, key in datatable['data']">
                                    <td>{{++key}}</td>
                                    <td>{{data.receipt_no}}</td>
                                    <td>{{data.payment_date}}</td>
                                    <td>{{data.extra_payment}}</td>
                                    <td>{{data.currency}}</td>
                                    <td>{{data.tax_amount}}</td>
                                    <td>{{data.amount}}</td>
                                    <td>{{data.total_amount}}</td>
                                    <td>{{data.remark}}</td>
                                    <td>
                                        <span v-if="data.status==1" class="badge badge-success">Processed</span>
                                        <span v-else-if="data.status==0" class="badge badge-warning">Pending</span>
                                        <span v-else class="badge badge-danger">Voided</span>
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
                </div>                    
            </b-modal>
        </b-form>
        
        <modal-loan-payment-form
            ref="childLoanPaymentForm"
            @emitEvent="emitEvent()"
            v-on:event_child="eventChild"> 
        </modal-loan-payment-form>
    </div>
</template>

<script>
    import ModalLoanPaymentForm from './_ModalLoanPaymentForm.vue'
    import {fetchLoanDataPayment} from '../../../../../../api/merchantAccount/accountLoanPayment'
    import {fetchDeliveryMethodList} from '../../../../../../api/setupmaster/deliveryMethod'
    import {createData,editData,updateData} from '../../../../../../api/merchantAccount/accountLoan'
    import {fetchAccount} from '../../../../../../api/merchantAccount/merchantAccount'
    import {fetchProdouctLoan} from '../../../../../../api/setupmaster/productLoan'
    import {fetchTermDayList} from '../../../../../../api/setupmaster/termDay'
    import {fetchPaymentCycleList} from '../../../../../../api/setupmaster/paymentCycle'
    import {fetchInterestMethodList} from '../../../../../../api/setupmaster/interestMethod'
    import Flash from '../../../../../../services/flash'

    export default{
        props:[
            'showAction',
            'account_id',            
            'account_master_id'
        ],
        data(){
			return{
                datatable:[],
                showForm:false,
                showAccountCredit:false,
                showLoanPayment:false,
                flash:Flash.state,
                account_loan_id:0,
                IsStatusApproved:true
            }
        },
        components:{
            ModalLoanPaymentForm
        },
        created(){
            this.showAction = this.showAction
        },
        methods:{
            toggleBodyClass(addRemoveClass, className) {
                const el = document.body;

                if (addRemoveClass === 'addClass') {
                el.classList.add(className);
                } else {
                el.classList.remove(className);
                }
            },
            emitEvent: function() {
                this.$emit('emitEvent')
            },
            eventChild: function(data) {
                this.showLoanPayment = true
                this.$emit('event_child', data)
            },
            // emit: function() {
			//     this.$emit('event_child', 1)
		    // },
            child_method(data){
                this.account_loan_id = data.account_loan_id
                this.showLoanPayment = true
                this.getAllLoanPayment(data)
            },
            viewLoanPaymentForm(flag,data){
                this.showLoanPayment = false
                this.$refs.childLoanPaymentForm.child_method(flag,data,this.account_loan_id)
            },
            getAllLoanPayment(data) {
                fetchLoanDataPayment(this.account_loan_id).then(response => {
                    this.datatable = response
                })
            },
            clearForm(){
                this.formData.account_id = this.account_id
                this.formData.loan_product_id = null
                this.formData.principle_amount = ''
                this.formData.loan_duration = ''
                this.formData.term_day_id = null
                this.formData.payment_cycle_id = null
                this.formData.expected_disbursement_date = ''
                this.formData.interest_method_id = null
                this.formData.currency_id = null
                this.formData.loan_interest = ''
                this.formData.grace_interest_charge = ''
                this.formData.loan_file = ''
                this.formData.loan_date = ''
                this.formData.approved_date = ''
                this.formData.request_date = ''
                this.formData.is_approved = false
                this.formData.remark = ''
            }
        }  
    }
</script>