import Component from 'ShopUi/models/component';

interface IFormElements extends HTMLFormElement{
    input?: HTMLInputElement;
    select?: HTMLSelectElement;
    textarea?: HTMLTextAreaElement;
}

export default class RegistrationNew extends Component {
    protected paymentIframe: HTMLIFrameElement;
    protected paymentIframeSrc: string;
    protected paymentForm: HTMLFormElement;
    protected paymentStepFormElements: IFormElements[];
    protected paymentMethodToggler: HTMLInputElement;
    protected paymentFormTogglers: HTMLInputElement[];
    protected creditCardPaymentToggler: HTMLInputElement;
    protected paymentName: string = 'heidelpayCreditCardSecure';
    protected paymentOptionsTogglers: HTMLInputElement[];
    protected serializeData: object;


    protected readyCallback(): void {
        this.paymentIframe = <HTMLIFrameElement>this.querySelector(`#${this.jsName}`);
        this.paymentIframeSrc = this.getDomainFromUrl;
        this.paymentForm = <HTMLFormElement>document.querySelector(this.paymentFormSelector);
        this.paymentStepFormElements = <IFormElements[]>Array.from(this.paymentForm.querySelectorAll('input, select, textarea'));
        this.paymentOptionsTogglers = <HTMLInputElement[]>Array.from(this.paymentForm.querySelectorAll(`input[name="${this.paymentOptionInputName}"`));
        this.creditCardPaymentToggler = <HTMLInputElement>this.paymentOptionsTogglers.find(toggler => toggler.value === this.name);
        this.paymentFormTogglers = <HTMLInputElement[]>Array.from(this.paymentForm.querySelectorAll(this.paymentTogglerSelector));
        this.paymentMethodToggler = <HTMLInputElement>this.paymentFormTogglers.find(toggler => toggler.value === this.paymentName);
        this.mapEvents();
    }

    protected serializeIframeForm(): object {
        this.serializeData = {};
        this.paymentStepFormElements.forEach(element => {
            if (element.name) {
                this.serializeData[element.name] = element.value;
            }
        });
        return this.serializeData;
    }

    protected isCreditCardNewRegistrationActive(): boolean {
        return this.paymentMethodToggler.checked && this.creditCardPaymentToggler.checked;
    }

    protected mapEvents(): void {
        this.paymentForm.addEventListener('submit', event => {
            if(this.isCreditCardNewRegistrationActive()) {
                const serializeForm = JSON.stringify(this.serializeIframeForm());

                event.preventDefault();
                this.paymentIframe.contentWindow.postMessage(serializeForm, this.paymentIframeSrc);
            }
        });
    }

    get paymentFormSelector(): string {
        return this.getAttribute('payment-form-selector');
    }
    
    get paymentOptionInputName(): string {
        return this.getAttribute('payment-option-input-name');
    }
    
    get paymentTogglerSelector(): string {
        return this.getAttribute('payment-toggler-selector');
    }

    get getDomainFromUrl(): string {
        const url = this.paymentIframe.getAttribute('src');
        const arr = url.split('/');
        return arr[0] + '//' + arr[2];
    }
}
