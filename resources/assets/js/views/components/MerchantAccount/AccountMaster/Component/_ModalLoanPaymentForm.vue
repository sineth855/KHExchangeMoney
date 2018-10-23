<template>
    <div>
        <b-form @submit="onSubmit" hide-footer>
            <b-modal centered :title="'Loan Payment Form'" ok-only size="md" class="modal-primary" v-model="showLoanPaymentForm" @ok="showLoanPaymentForm = false">
                <b-alert v-if="flash.success" variant="success" show>{{ flash.success }}</b-alert>
                <b-alert v-if="flash.error" variant="danger" show>{{ flash.error }}</b-alert>
                <div class="row">

                    <div class="col-sm-12">
                        <b-form-group id="exampleInputGroup3"
                            label="Payment Currency *"
                            label-for="exampleInput3">
                            <b-form-select id="exampleInput3"
                                            :options="currency"
                                            required
                                            v-model="formData.currency">
                            </b-form-select>
                        </b-form-group>
                    </div>
                    
                    <input type="hidden" v-model="formData.account_loan_id"/>

                    <div class="col-sm-12">
                        <b-form-fieldset label="Amount *">
                            <b-form-input type="number" v-model="formData.amount" required placeholder="Expected Disbursement Date"></b-form-input>
                        </b-form-fieldset>
                    </div>
                    
                    <div class="col-sm-12">
                        <b-form-fieldset label="Extra Payment">
                            <b-form-input type="number" v-model="formData.extra_payment" placeholder="Extra Payment"></b-form-input>
                        </b-form-fieldset>
                    </div>
                    
                    <div class="col-sm-12">
                        <b-form-fieldset label="Remarks">
                            <b-form-input type="text" v-model="formData.remark" placeholder="Remark"></b-form-input>
                        </b-form-fieldset>
                    </div>
                </div>
                <div slot="modal-footer" class="w-100">
                    <span class="pull-right">
                        <b-button type="reset" @click="onCloseLoanForm()" variant="default"><i class="fa fa-ban"></i> Cancel</b-button>
                        <!--<button v-on:click="emit">Clique me to emit from child component</button>-->
                        <b-button v-if="IsStatusApproved" type="submit" variant="primary">Submit</b-button>
                    </span>
                </div>
            </b-modal>
        </b-form>
    </div>
</template>

<script>
    import {createData,editData,updateData} from '../../../../../../api/merchantAccount/accountLoanPayment'
    import {fetchCurrencyList} from '../../../../../../api/setupmaster/currency'
    import Flash from '../../../../../../services/flash'

    export default{
        props:[
        ],
        data(){
			return{
                showForm:false,
                showAccountCredit:false,
                showLoanPaymentForm:false,
                flash:Flash.state,
                formData:{
                    account_loan_id:0,
                    currency:null,
                    extra_payment:0,
                    amount:0,
                    remark:''
                },
                currency:[{text:'Choose Currency', value: null }],
                account_loan_id:0,
                IsStatusApproved:true,
                flag:0
            }
        },
        created(){
            this.showAction = this.showAction
            this.getAllCurrency()
        },
        methods:{
            onCloseLoanForm() {
                // this.$emit('emitEvent')
                this.$emit('event_child', this.account_id)
                this.showLoanPaymentForm = false
            },
            child_method(flag,data,account_loan_id){
                this.flag = flag
                this.account_loan_id = account_loan_id
                this.formData.account_loan_id = account_loan_id
                this.showLoanPaymentForm = true
            },
            getAllCurrency() {
                fetchCurrencyList().then(response => {
                    // this.datatable = response
                    for(let x=0 ; x <= response['data'].length-1 ; x++){
                        let data = {'text':response['data'][x]['code'],'value':response['data'][x]['code']}
                        this.currency.push(data)
                    }
                })
            },
            clearForm(){
                this.formData
            },
            onSubmit (evt) {
                evt.preventDefault();
                if(this.flag==1){
                    createData(this.formData).then(response => {
                        if(response.success == true){
                            Flash.setSuccess(response.message)
                            // this.$emit('event_child', this.account_id)
                            this.showLoanPaymentForm = false
                            this.clearForm()
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
                    updateData(this.formData,this.account_loan_id).then(response => {
                        if(response.success == true){
                            Flash.setSuccess(response.message)
                            // this.$emit('event_child', this.account_id)
                            this.showLoanPaymentForm = false
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