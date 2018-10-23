import axios from 'axios'
import config from '../config'
import Flash from '../../services/flash'
var url = '/api/check_authentication';

export function requireAuth(to, from, next) {  
	axios.get(url)
	.then(response => {
		if(response.data.success==true){
			next();
		}else{
			next("pages/login");
		}
	})
}