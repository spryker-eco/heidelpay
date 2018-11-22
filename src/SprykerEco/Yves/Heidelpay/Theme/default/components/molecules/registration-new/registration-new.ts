import Component from 'ShopUi/models/component';

interface IFormElements extends HTMLFormElement{
    input?: HTMLInputElement,
    select?: HTMLSelectElement,
    textarea?: HTMLTextAreaElement
}

export default class RegistrationNew extends Component {
    protected paymentIframeSelector: HTMLIFrameElement;
    protected paymentStepForm: HTMLFormElement;
    protected creditCardPaymentOptionSelector: HTMLInputElement;
    protected paymentOptionDivsSelector: HTMLElement;
    protected serializeData: object;
    protected paymentStepFormElements: Array<IFormElements>
    protected paymentIframeSrc: string;
    // TODO
    protected checkedCreditCardPaymentOptionSelector: HTMLInputElement;
    protected paymentIframe: HTMLIFrameElement;


    protected readyCallback(): void {
        this.paymentIframeSelector = <HTMLIFrameElement>this.querySelector(`#${this.jsName}`);
        this.paymentIframeSrc = this.getDomainFromUrl;
        this.paymentStepForm = <HTMLFormElement>document.getElementById(this.paretnFormId);
        this.paymentStepFormElements = <IFormElements[]>Array.from(this.paymentStepForm.querySelectorAll('input, select, textarea'));
        // console.log(this);
        // console.log(this.paymentStepFormElements);
    }

    protected serializeIframeForm() {
        this.serializeData = {};
        this.paymentStepFormElements.forEach(element => {
            if (element.name) {
                this.serializeData[element.name] = element.value; 
            }
        })
    }

    protected showActivePaymentOption() {
        this.paymentOptionDivsSelector.classList.add('is-hidden');
        const activeOptionValue = this.checkedCreditCardPaymentOptionSelector.value;


        if (activeOptionValue !== undefined) {
            const paymentOption = document.getElementById(`#payment-option-${activeOptionValue}`);
            paymentOption.classList.remove('is-hidden');
        }
    }

    protected paymentFrameFormSubmit(): void {
        this.paymentStepForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const data = this.serializeIframeForm();
            const paymentIframe = this.paymentIframe;
            paymentIframe.contentWindow.postMessage(JSON.stringify(data), this.targetOrigin);
        })
    }

    get paretnFormId(): string {
        return this.getAttribute('parent-form-id');
    }

    get getDomainFromUrl(): string {
        const url = this.paymentIframeSelector.getAttribute('src');
        const arr = url.split("/");
        return arr[0] + "//" + arr[2];
    }
}
