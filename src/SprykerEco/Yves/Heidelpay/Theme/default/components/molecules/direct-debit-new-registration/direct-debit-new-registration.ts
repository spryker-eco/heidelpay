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
        this.directDebitPaymentToggler = <HTMLInputElement>this.paymentOptionsTogglers.find(toggler => toggler.value === 'new-registration');
        this.paymentFormTogglers = <HTMLInputElement[]>Array.from(this.paymentForm.querySelectorAll(this.paymentTogglerSelector));
        this.paymentMethodToggler = <HTMLInputElement>this.paymentFormTogglers.find(toggler => toggler.value === this.paymentName);
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.paymentForm.addEventListener('submit', this.handleSubmit.bind(this));
    }

    protected handleSubmit(event): void {
        event.preventDefault();

        if(this.isDirectDebitNewRegistrationActive()) {
            this.setupFormAttributes();
            console.log(this.paymentForm);
            debugger;
            this.paymentForm.submit();
        }

    }

    protected disableFormFields(): void {
        const elements = Array.from(this.paymentForm.elements);
        // elements.forEach(element => {
        //     console.log(element.name);
        // })
    }

    protected setupFormAttributes(): void {
        this.paymentForm.action = this.formUrl;
    }

    protected isDirectDebitNewRegistrationActive(): boolean {
        return this.paymentMethodToggler.checked && this.directDebitPaymentToggler.checked;
    }

    get paymentOptionInputName(): string {
        return this.getAttribute('payment-option-input-name');
    }

    get paymentTogglerSelector(): string {
        return this.getAttribute('payment-toggler-selector');
    }

    protected get formUrl(): string {
        return this.getAttribute('url');
    }

    protected get parentFormId(): string {
        return this.getAttribute('payment-form-id');
    }
}
