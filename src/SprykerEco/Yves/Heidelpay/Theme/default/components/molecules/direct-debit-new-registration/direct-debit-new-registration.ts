import Component from 'ShopUi/models/component';


export default class DirectDebitNewRegistration extends Component {

    protected xhr: XMLHttpRequest;
    protected inputs: HTMLInputElement[];
    protected parentForm: HTMLFormElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.parentForm = <HTMLFormElement>document.getElementById(this.parentFormId);
        this.inputs = Array.from(this.getElementsByTagName('input'));
        this.xhr = new XMLHttpRequest();
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.parentForm.addEventListener('submit', this.handleSubmit.bind(this));
    }

    protected handleSubmit(event): void {
        event.preventDefault();
        this.handleRequest();
    }

    protected createRequestBody(): string {
        let reqBody = '';
        this.inputs.map((input, index) => {
            reqBody += `${(index !== 0 ? '&' : '') + input.name}=${encodeURIComponent(input.value)}`;
        });
        console.log(reqBody);
        return reqBody;
    }

    protected handleRequest(): void {
        new Promise((resolve, reject) => {
            const reqBody = this.createRequestBody();
            this.xhr.withCredentials = true;
            this.xhr.open(this.formMethod, this.formUrl);
            this.xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            this.xhr.setRequestHeader("Accept", "*/*");
            this.xhr.setRequestHeader("Cache-Control", "no-cache");
            this.xhr.addEventListener('load', (event: Event) => this.onRequestLoad(resolve, reject));
            this.xhr.addEventListener('error', (event: Event) => this.onRequestError(reject));
            this.xhr.send(reqBody);
        })
    }

    protected onRequestLoad(resolve, reject): void {
        if(this.xhr.status === 200){
            resolve(this.xhr.response);
            this.parentForm.submit();
            return;
        }
        this.onRequestError(reject);
    }

    protected onRequestError(reject): void {
        this.errorMessage();
        reject(new Error(`${this.formUrl} request aborted with ${this.xhr.status}`));
    }

    protected errorMessage(): void {

    }

    protected get formUrl(): string {
        return this.getAttribute('url');
    }

    protected get formMethod(): string {
        return this.getAttribute('method');
    }

    protected get parentFormId(): string {
        return this.getAttribute('payment-form-id');
    }
}
