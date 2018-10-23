<template>
    <div>
        <b-form @submit="onSubmit" hide-footer>
            <b-modal centered :title="'Account Credit Form : '" ok-only size="md" class="modal-primary" v-model="showCreditForm" @ok="showCreditForm = false">
                <b-alert v-if="flash.success" variant="success" show>{{ flash.success }}</b-alert>
                <b-alert v-if="flash.error" variant="danger" show>{{ flash.error }}</b-alert>
                <div class="row">
                    <div class="col-sm-12">
                        <b-form-fieldset label="Credit Amount *">
                        <b-form-input type="text" v-model="formData.credit_amount" required placeholder="Credit Amount"></b-form-input>
                        </b-form-fieldset>
                    </div>
                    <div class="col-sm-12">
                        <b-form-fieldset>
                            <b-form-checkbox v-model="formData.is_active"> Active Account Immediately?</b-form-checkbox>
                        </b-form-fieldset>
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
    import {createData,editData,updateData} from '../../../../../../api/merchantAccount/accountCredit'
    import Flash from '../../../../../../services/flash'

    export default{
        props:[
            'showAction',
            'account_id',
            'flag'
        ],
        data(){
			return{
                showForm:false,
                showAccountCredit:false,
                showCreditForm:false,
                flash: Flash.state,
                formData:{
                    account_id:this.account_id,
                    credit_amount:'',
                    // status:true,
                    is_active:false
                },
                account_credit_id:0,
                IsStatusApproved:true
            }
        },
        created(){
            this.showAction = this.showAction
        },
        methods:{
            // emit: function() {
			//     this.$emit('event_child', 1)
		    // },
            child_method(flag,data){
                this.showCreditForm = true
                this.formData.credit_amount = data.credit_amount
                this.account_credit_id = data.account_credit_id
                if(flag!=1){
                    // if(data.is_active){
                    //     this.formData.is_active = true
                    //     this.IsStatusApproved = false
                    // }else{
                    //     this.formData.is_active = false
                    //     this.IsStatusApproved = true
                    // }
                    if(data.is_active){
                        this.formData.is_active = true
                        this.IsStatusApproved = true
                    }else{
                        this.formData.is_active = false
                        this.IsStatusApproved = true
                    }
                }else{
                    this.clearForm()
                }
            },
            clearForm(){
                this.formData.credit_amount= ''
                this.formData.is_active = false
                this.IsStatusApproved = true
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
                            this.clearForm()
                            // this.getData()
                        }else{
                            Flash.setError(response.message)
                        }
                    })
                }else{
                    updateData(this.formData,this.account_credit_id).then(response => {
                            if(response.success == true){
                            this.$emit('event_child', this.account_id)
                            this.successModal = false
                            this.showCreditForm = false
                            Flash.setSuccess(response.message)
                            // this.getData()
                        }else{
                            Flash.setError(response.message)
                        }
                    })
                }
                
            }
        }  
    }
</script>