import { configure, defineRule, ErrorMessage, Field, Form } from "vee-validate";
import {
  confirmed,
  email,
  length,
  max,
  min,
  numeric,
  regex,
  required,
} from "@vee-validate/rules";
import en from "@vee-validate/i18n/dist/locale/en.json";
import { localize } from "@vee-validate/i18n";
import th from "@vee-validate/i18n/dist/locale/th.json";

// [PSC: 2022-04-12] Load locale files dynamically
configure({
  generateMessage: localize({
    en,
    th,
  }),
  validateOnInput: true,
});

defineRule("confirmed", confirmed);
defineRule("email", email);
defineRule("length", length);
defineRule("max", max);
defineRule("min", min);
defineRule("numeric", numeric);
defineRule("regex", regex);
defineRule("required", required);

export { ErrorMessage, Field, Form };
