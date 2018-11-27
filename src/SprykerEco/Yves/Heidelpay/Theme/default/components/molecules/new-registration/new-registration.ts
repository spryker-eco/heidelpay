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
        this.paymentFrameFormSubmit();
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
        let response = false;
        this.paymentFormSelections.forEach(formSelection => {
            this.creditCardPaymentOptionSelectors.forEach(creditCardSelector => {
                if(formSelection.value === this.paymentName &&
                    creditCardSelector.value === this.name) {
                    response = true;
                }
            })
        });
        return response;
    }

    protected paymentFrameFormSubmit(): void {
        this.paymentStepForm.addEventListener('submit', this.submitHandler.bind(this));
    }
    
    protected submitHandler(event: Event): void {
        if(this.isCreditCardNewRegistrationActive()) {
            event.preventDefault();
            this.paymentIframe.contentWindow.postMessage(JSON.stringify(this.serializeIframeForm()), this.paymentIframeSrc);
        }
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
