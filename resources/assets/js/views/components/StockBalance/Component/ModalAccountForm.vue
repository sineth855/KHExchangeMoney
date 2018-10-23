<template>
    <div>
        <b-form @submit="onSubmit" hide-footer>
            <b-modal centered :title="'Account : '+account_no" ok-only size="lg" class="modal-primary" v-model="successModal" @ok="successModal = false">
                <b-alert v-if="flash.success" variant="success" show>{{ flash.success }}</b-alert>
                <b-alert v-if="flash.error" variant="danger" show>{{ flash.error }}</b-alert>
                <!--{{accountData | json}}-->
                <div class="row">
                    <div class="col-sm-12">
                        <!--Here is flag to check user update account info or create new (1=create new, 2=update)-->
                        <input type="hidden" v-model="flag_account" placeholder="Flag"/>
                        <input type="hidden" v-model="formData.account_master_id" placeholder="Account Master ID"/>
                    </div>
                    <div class="col-sm-6">
                        <b-form-fieldset label="Account No">
                        <b-form-input type="text" v-model="formData.account_no" disabled placeholder="Account No"></b-form-input>
                        </b-form-fieldset>
                    </div>
                    <div class="col-sm-6">
                        <b-form-fieldset label="Account Code">
                        <b-form-input type="text" v-model="formData.account_code" disabled placeholder="Account Code"></b-form-input>
                        </b-form-fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">

                        <b-form-group id="exampleInputGroup3"
                                    label="Transaction Rule"
                                    label-for="exampleInput3">
                        <b-form-select id="exampleInput3"
                                        :options="transactionRule"
                                        required
                                        v-model="formData.transaction_rule_id">
                        </b-form-select>
                        </b-form-group>

                    </div>

                    <div class="col-sm-6">
                        <b-form-group id="exampleInputGroup3"
                            label="Currency"
                            label-for="exampleInput3">
                        <b-form-select id="exampleInput3"
                                        :options="currency"
                                        required
                                        v-model="formData.currency_id">
                        </b-form-select>
                        </b-form-group>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <b-form-group id="exampleInputGroup3"
                            label="Account Type"
                            label-for="exampleInput3">
                        <b-form-select id="exampleInput3"
                                        :options="accountType"
                                        required
                                        v-model="formData.account_type_id">
                        </b-form-select>
                        </b-form-group>
                    </div>

                    <div class="col-sm-6">
                        <b-form-group id="exampleInputGroup3"
                            label="Account Rule"
                            label-for="exampleInput3">
                        <b-form-select id="exampleInput3"
                                        :options="accountRule"
                                        required
                                        v-model="formData.account_rule_id">
                        </b-form-select>
                        </b-form-group>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <b-form-fieldset label="Order Level">
                        <b-form-input type="text" v-model="formData.country" id="country" placeholder="Order Level"></b-form-input>
                        </b-form-fieldset>
                    </div>
                </div>

                <b-form-fieldset>
                    <b-form-checkbox v-model="formData.is_default"> Is Default?</b-form-checkbox>
                </b-form-fieldset>

                <b-form-fieldset>
                    <b-form-checkbox v-model="formData.is_active"> Is Active?</b-form-checkbox>
                </b-form-fieldset>

                <b-form-fieldset>
                    <b-form-checkbox v-model="formData.status"> Status</b-form-checkbox>
                </b-form-fieldset>

                <div slot="modal-footer" class="w-100">
                    <span class="pull-right">
                        <b-button type="reset" @click="changeValue()" variant="default"><i class="fa fa-ban"></i> Cancel</b-button>
                        <b-button type="submit" variant="primary">Submit</b-button>
                    </span>
                </div>

            </b-modal>
        </b-form>
    </div>
</template>

<script>
    import {fetchAccount,fetchAccountDetail,createAccount,updateAccount} from '../../../../../../api/merchantAccount/merchantAccount'
    import Flash from '../../../../../../services/flash'

    export default{
        props:[
                'account_no',
                'flag_account',
                'formData',
                'account_master_id',
                'target_prop',
             ],
        data(){
            return{
                transactionRule: [
                    { text: 'Select Transaction Rule', value: null },
                    {text: 'Accrual Upfront',value:1}, 
                    {text:'Accrual Upfront',value:2}, 
                    {text:'Cash Based',value:3}
                ],
                currency: [
                    { text: 'Select One', value: null },
                    {text: 'USD',value:1}, 
                    {text:'POUND',value:2}, 
                    {text:'KHR',value:3}, 
                    {text:'BATH',value:4}
                ],
                accountRule: [
                    { text: 'Select One', value: null },
                    {text: 'Accrual Upfront',value:1}, 
                    {text:'Accrual Backward',value:2}
                ],
                accountType: [
                    { text: 'Select One', value: null },
                    {text: 'Coporate',value:1}, 
                    {text:'Individual',value:2}
                ],
                flash: Flash.state,
                tabIndex:this.tabIndex,
                modalFormCredit:false,
                modalAccountDetail:false,
                successModal: false
            }
        },
        mounted() {
            
        },
        created() {
          
        }, 
        watch: {
            target_prop: function() {
                if(this.target_prop == your_expected_value) {
                    //execute the desired method for this component
                }
            }
        },
        methods:{
            child_method(){
                this.successModal = true
            },
            onSubmit (evt) {
                evt.preventDefault();
                if(this.flag_account==1){
                // this.flag_account == 1 do create new account;
                createAccount(this.formData).then(response => {
                    if(response.success == true){
                    Flash.setSuccess(response.message)
                    this.successModal = false
                    this.getData()
                    }else{
                    Flash.setError(response.message)
                    }
                })
                }else{
                // this.flag_account == 2 do update account;
                updateAccount(this.formData,this.account_master_id).then(response => {
                    if(response.success == true){
                    Flash.setSuccess(response.message)
                    this.getData()
                    }else{
                    Flash.setError(response.message)
                    }
                })
                }
                
            },
        }
    }
</script>