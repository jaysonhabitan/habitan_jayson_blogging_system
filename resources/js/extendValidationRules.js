import { extend } from 'vee-validate';
import { required, email } from 'vee-validate/dist/rules';

function extendValidationRules() {
  extend('required', {
    ...required,
    message: '{_field_} is required',
  });

  extend('email', {
    ...email,
    message: 'Enter a valid email',
  });
}

export default extendValidationRules;
