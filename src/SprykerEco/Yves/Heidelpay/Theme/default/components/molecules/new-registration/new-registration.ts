import Component from 'ShopUi/models/component';

interface IFormElements extends HTMLFormElement{
    input?: HTMLInputElement,
    select?: HTMLSelectElement,
    textarea?: HTMLTextAreaElement
}

export default class RegistrationNew extends Component {
    protected paymentIframe: HTMLIFrameElement;
    protected paymentIframeSrc: string;
    protected paymentStepForm: HTMLFormElement;
    protected paymentStepFormElements: Array<IFormElements>;
    protected paymentFormSelections: Array<HTMLInputElement>;
    protected paymentName: string = 'heidelpayCreditCardSecure';
    protected creditCardPaymentOptionSelectors: Array<HTMLInputElement>;
    protected serializeData: object;

    protected readyCallback(): void {
        this.paymentIframe = <HTMLIFrameElement>this.querySelector(`#${this.jsName}`);
        this.paymentIframeSrc = this.getDomainFromUrl;
        this.paymentStepForm = <HTMLFormElement>document.getElementById(this.paretnFormId);
        this.paymentStepFormElements = <IFormElements[]>Array.from(this.paymentStepForm.querySelectorAll('input, select, textarea'));
        this.creditCardPaymentOptionSelectors = <HTMLInputElement[]>Array.from(this.paymentStepForm.querySelectorAll(`input[name="${this.paymentInputName}"`));
        this.paymentFormSelections = <HTMLInputElement[]>Array.from(this.paymentStepForm.querySelectorAll(`input[name="${this.paymentTogglerName}"]`));
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
        const paymentMethodSelected = this.paymentFormSelections.find(formSelection => formSelection.checked);
        const creditCardTypeSelected = this.creditCardPaymentOptionSelectors.find(creditCardSelector => creditCardSelector.checked);
        return (paymentMethodSelected &&
                creditCardTypeSelected &&
                paymentMethodSelected.value === this.paymentName &&
                creditCardTypeSelected.value === this.name) ? true : false;
    }

    protected mapEvents(): void {
        this.paymentStepForm.addEventListener('submit', event => {
            if(this.isCreditCardNewRegistrationActive()) {
                const serializeForm = JSON.stringify(this.serializeIframeForm());

                event.preventDefault();
                this.paymentIframe.contentWindow.postMessage(serializeForm, this.paymentIframeSrc);
            }
        });
    }

    get paretnFormId(): string {
        return this.getAttribute('parent-form-id');
    }
    
    get paymentInputName(): string {
        return this.getAttribute('payment-input-name');
    }
    
    get paymentTogglerName(): string {
        return this.getAttribute('payment-toggler-name');
    }

    get getDomainFromUrl(): string {
        const url = this.paymentIframe.getAttribute('src');
        const arr = url.split("/");
        return arr[0] + "//" + arr[2];
    }
}
