import Component from 'ShopUi/models/component';

export default class DirectDebitNewRegistration extends Component {
    protected inputs: HTMLInputElement[];
    protected paymentForm: HTMLFormElement;
    protected paymentMethodToggler: HTMLInputElement;
    protected paymentFormTogglers: HTMLInputElement[];
    protected directDebitPaymentToggler: HTMLInputElement;
    protected paymentName: string = 'heidelpayDirectDebit';
    protected paymentOptionsTogglers: HTMLInputElement[];

    protected readyCallback(): void {}

    protected init(): void {
        this.paymentForm = <HTMLFormElement>document.getElementById(this.parentFormId);
        this.inputs = Array.from(this.getElementsByTagName('input'));
        this.paymentOptionsTogglers = <HTMLInputElement[]>Array.from(this.paymentForm.querySelectorAll(`input[name="${this.paymentOptionInputName}"`));
        this.directDebitPaymentToggler = <HTMLInputElement>this.paymentOptionsTogglers.find(toggler => toggler.value === this.name);
        this.paymentFormTogglers = <HTMLInputElement[]>Array.from(this.paymentForm.querySelectorAll(this.paymentTogglerSelector));
        this.paymentMethodToggler = <HTMLInputElement>this.paymentFormTogglers.find(toggler => toggler.value === this.paymentName);
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.paymentForm.addEventListener('submit', this.onSubmit.bind(this));
    }

    protected onSubmit(event: Event): void {
        if (!this.isDirectDebitNewRegistrationActive()) {
            return;
        }
        event.preventDefault();
        this.changeFormAction();
        this.disableFormFields();
        this.enableDirectDebitInputs();
        this.paymentForm.submit();
    }

    protected disableFormFields(): void {
        Array.from(this.paymentForm.elements).forEach((element: HTMLInputElement) => {
            if (element.type !== 'submit') {
                element.disabled = true;
            }
        });
    }

    protected enableDirectDebitInputs(): void {
        this.inputs.forEach(input => {
            input.disabled = false;
        });
    }

    protected changeFormAction(): void {
        this.paymentForm.action = this.formUrl;
    }

    protected isDirectDebitNewRegistrationActive(): boolean {
        return this.paymentMethodToggler.checked && this.directDebitPaymentToggler.checked;
    }

    protected get paymentOptionInputName(): string {
        return this.getAttribute('payment-option-input-name');
    }

    protected get paymentTogglerSelector(): string {
        return this.getAttribute('payment-toggler-selector');
    }

    protected get formUrl(): string {
        return this.getAttribute('url');
    }

    protected get parentFormId(): string {
        return this.getAttribute('payment-form-id');
    }
}
