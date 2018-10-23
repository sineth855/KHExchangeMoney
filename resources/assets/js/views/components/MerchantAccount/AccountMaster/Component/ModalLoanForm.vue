<template>
    <div>
        <b-form @submit="onSubmit" hide-footer>
            <b-modal centered :title="'Loan Form : '" ok-only size="md" class="modal-primary" v-model="showCreditForm" @ok="showCreditForm = false">
                <b-alert v-if="flash.success" variant="success" show>{{ flash.success }}</b-alert>
                <b-alert v-if="flash.error" variant="danger" show>{{ flash.error }}</b-alert>
                <div class="row">
                    <div class="col-sm-12">
                        <b-form-group id="exampleInputGroup3"
                                    label="Choose Account *"
                                    label-for="exampleInput3">
                            <b-form-select id="exampleInput3"
                                            :options="accountCurrency"
                                            required
                                            v-model="formData.account_id">
                            </b-form-select>
                        </b-form-group>
                    </div>

                    <div class="col-sm-12">
                        <b-form-group id="exampleInputGroup3"
                                    label="Loan Product *"
                                    label-for="exampleInput3">
                            <b-form-select id="exampleInput3"
                                            :options="loanProduct"
                                            required
                                            v-model="formData.loan_product_id">
                            </b-form-select>
                        </b-form-group>
                    </div>
                    
                    <div class="col-sm-12">
                        <b-form-fieldset label="Principle Amount *">
                            <b-form-input autocomplete="off" type="number" v-model="formData.principle_amount" required placeholder="Principle Amount"></b-form-input>
                        </b-form-fieldset>
                    </div>

                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6">
                            <b-form-fieldset label="Loan Duration">
                                <b-form-input autocomplete="off" type="number" v-model="formData.loan_duration" required placeholder="Loan Duration"></b-form-input>
                            </b-form-fieldset>
                            </div>
                            <div class="col-sm-6">
                                <b-form-group id="exampleInputGroup3"
                                        label="Term Day *"
                                        label-for="exampleInput3">
                                <b-form-select id="exampleInput3"
                                                :options="termDay"
                                                required
                                                v-model="formData.term_day_id">
                                </b-form-select>
                            </b-form-group>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <b-form-group id="exampleInputGroup3"
                                    label="Payment Cycle *"
                                    label-for="exampleInput3">
                            <b-form-select id="exampleInput3"
                                            :options="paymentCycle"
                                            required
                                            v-model="formData.payment_cycle_id">
                            </b-form-select>
                        </b-form-group>
                    </div>

                    <div class="col-sm-12">
                        <b-form-group id="exampleInputGroup3"
                                    label="Interest Method *"
                                    label-for="exampleInput3">
                            <b-form-select id="exampleInput3"
                                            :options="interestMethod"
                                            required
                                            v-model="formData.interest_method_id">
                            </b-form-select>
                        </b-form-group>
                    </div>

                    <div class="col-sm-12">
                        <b-form-fieldset label="Expected Disbursement Date *">
                            <b-form-input type="date" v-model="formData.expected_disbursement_date" required placeholder="Expected Disbursement Date"></b-form-input>
                        </b-form-fieldset>
                    </div>
                    
                    <div class="col-sm-12">
                        <b-form-fieldset label="Loan Date *">
                            <b-form-input type="date" v-model="formData.loan_date" required placeholder="Loan Date"></b-form-input>
                        </b-form-fieldset>
                    </div>
                    
                    <div class="col-sm-12">
                        <b-form-fieldset label="Remark *">
                            <b-form-input type="text" v-model="formData.remark" required placeholder="Remark"></b-form-input>
                        </b-form-fieldset>
                    </div>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <b-form-fieldset>
                                    <b-form-checkbox v-model="formData.is_approved">Is Approved?</b-form-checkbox>
                                </b-form-fieldset>
                            </div>
                            <!--<div class="col-sm-6">
                                <b-form-fieldset>
                                    <b-form-checkbox v-model="formData.status">Status</b-form-checkbox>
                                </b-form-fieldset>
                            </div>-->
                        </div>
                    </div>
                </div>
                <div slot="modal-footer" class="w-100">
                    <span class="pull-right">
                        <b-button type="reset" @click="showCreditForm = false" variant="default"><i class="fa fa-ban"></i> Cancel</b-button>
                        <!--<button v-on:click="emit">Clique me to emit from child component</button>-->
                        <b-button v-if="IsStatusApproved" type="submit" variant="primary">Submit</b-button>
                    </span>
                </div>
            </b-modal>
        </b-form>
    </div>
</template>

<script>
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
            'accountCurrencyData',
            'account_master_id',
            'flag'
        ],
        data(){
			return{
                showForm:false,
                showAccountCredit:false,
                showCreditForm:false,
                flash:Flash.state,
                deliveryMethod:[{text:'Choose Delivery Method', value: null }],
                accountCurrency:[{text:'Choose Account', value: null }],
                loanProduct:[{text:'Choose Product', value: null }],
                termDay:[{text:'Choose Term', value: null }],
                paymentCycle:[{text:'Choose Payment Cycle', value: null }],
                interestMethod:[{text:'Choose Interest Method', value: null }],
                formData:{
                    account_id:this.account_id,
                    loan_product_id:null,
                    principle_amount:'',
                    loan_duration:'',
                    term_day_id:null,
                    payment_cycle_id:null,
                    expected_disbursement_date:'',
                    interest_method_id:null,
                    currency_id:null,
                    loan_interest:'',
                    grace_interest_charge:'',
                    loan_file:'',
                    loan_date:'',
                    approved_date:'',
                    request_date:'',
                    is_approved:false,
                    remark:''
                },
                account_loan_id:0,
                IsStatusApproved:true
            }
        },
        created(){
            this.showAction = this.showAction
            this.getAccount()
            this.getAllProdouctLoan()
            this.getAllTermDay()
            this.getAllPaymentCycle()
            this.getAllInterestMethod()
        },
        methods:{
            getAccount() {
                // fetchAccount(this.account_master_id).then(response => {
                //     // this.accountCurrency = response
                //     for(let x = 0 ; x <= response['data'].length - 1 ; x++){
                //         let dataAccount = {'text':response['data'][x]['account_no']+'('+response['data'][x]['currency']+')','value':response['data'][x]['account_id']}
                //         this.accountCurrency.push(dataAccount)
                //     }
                // })
                const AccCurentcy = this.accountCurrencyData.data
                for(let x = 0 ; x <= AccCurentcy.length - 1 ; x++){
                    let dataAccount = {'text':AccCurentcy[x]['account_no']+'('+AccCurentcy[x]['currency']+')','value':AccCurentcy[x]['account_id']}
                    this.accountCurrency.push(dataAccount)
                }
            },
            getAllProdouctLoan(){
                fetchProdouctLoan().then(response => {
                    for(let x = 0 ; x <= response['data'].length - 1 ; x++){
                        let data = {'text':response['data'][x]['name'],'value':response['data'][x]['loan_product_id']}
                        this.loanProduct.push(data)
                    }
                })
            },
            getAllTermDay(){
                fetchTermDayList().then(response => {
                    for(let x = 0 ; x <= response['data'].length - 1 ; x++){
                        let data = {'text':response['data'][x]['name'],'value':response['data'][x]['term_day_id']}
                        this.termDay.push(data)
                    }
                })
            },
            getAllPaymentCycle(){
                fetchPaymentCycleList().then(response => {
                    for(let x = 0 ; x <= response['data'].length - 1 ; x++){
                        let data = {'text':response['data'][x]['name'],'value':response['data'][x]['payment_cycle_id']}
                        this.paymentCycle.push(data)
                    }
                })
            },
            getAllInterestMethod(){
                fetchInterestMethodList().then(response => {
                    for(let x = 0 ; x <= response['data'].length - 1 ; x++){
                        let data = {'text':response['data'][x]['name'],'value':response['data'][x]['interest_method_id']}
                        this.interestMethod.push(data)
                    }
                })
            },
            // emit: function() {
			//     this.$emit('event_child', 1)
		    // },
            child_method(flag,data){
                this.showCreditForm = true
                if(flag!=1){
                    this.formData.account_id = data.account_id
                    this.formData.loan_product_id = data.loan_product_id
                    this.formData.principle_amount = data.principle_amount
                    this.formData.loan_duration = data.loan_duration
                    this.formData.term_day_id = data.term_day_id
                    this.formData.payment_cycle_id = data.payment_cycle_id
                    this.formData.expected_disbursement_date = data.expected_disbursement_date
                    this.formData.interest_method_id = data.interest_method_id
                    this.formData.currency_id = data.currency_id
                    this.formData.loan_interest = data.loan_interest
                    this.formData.grace_interest_charge = data.grace_interest_charge
                    this.formData.loan_file = data.loan_file
                    this.formData.loan_date = data.loan_date
                    this.formData.approved_date = data.approved_date
                    this.formData.request_date = data.request_date
                    if(data.is_approved==1){
                        this.formData.is_approved = true
                    }else{
                        this.formData.is_approved = false
                    }
                    this.formData.remark = data.remark
                    this.account_loan_id = data.account_loan_id
                    if(data.is_approved){
                        this.IsStatusApproved = false
                    }else{
                        this.IsStatusApproved = true
                    }
                }else{
                    this.IsStatusApproved = true
                    this.clearForm()
                }
            },
            getAllDeliveryMethod() {
                fetchDeliveryMethodList().then(response => {
                // this.datatable = response
                for(let x=0 ; x <= response['data'].length-1 ; x++){
                    let data = {'text':response['data'][x]['delivery_method'],'value':response['data'][x]['delivery_method_id']}
                    this.deliveryMethod.push(data)
                }
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
            },
            // getData(dataAccounCredit) {
            //     console.log(dataAccounCredit)
            //     this.formData.credit_amount = dataAccounCredit['deposit_amount']
            // },
            onSubmit (evt) {

                evt.preventDefault();
                if(this.flag==1){
                    createData(this.formData).then(response => {
                        if(response.success == true){
                            Flash.setSuccess(response.message)
                            this.$emit('event_child', this.account_id)
                            this.successModal = false
                            this.showCreditForm = false
                            // this.clearForm()
                            // this.getData()
                        }else{
                            Flash.setError(response.message)
                        }
                    }).catch(e => {
                        Flash.setError("Error while trying to create data!")
                        this.showCreditForm = false
                        // this.errors.push(e)
                    })
                }else{
                    updateData(this.formData, this.account_loan_id).then(response => {
                            if(response.success == true){
                            Flash.setSuccess(response.message)
                            this.$emit('event_child', this.account_id)
                            this.successModal = false
                            this.showCreditForm = false
                            this.clearForm()
                            // this.getData()
                        }else{
                            Flash.setError(response.message)
                            this.showCreditForm = false
                        }
                    }).catch(e => {
                        Flash.setError("Error while trying to update data!")
                        this.showCreditForm = false
                        // this.errors.push(e)
                    })
                }
                
            }
        }  
    }
</script>