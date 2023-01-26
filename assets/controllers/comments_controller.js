import { Controller } from 'stimulus';

export default class extends Controller {
	connect() {
		this.baseForm = this.element.querySelector( '.comments__form' );
		this.replyForm = null;
		this.replyButton = null;
	}

	replyButtonClick( e ) {
		this.removeReplyForm(); // remove any existing reply form

		this.replyButton = e.target;
		this.replyForm = this.baseForm.cloneNode( true );

		const parentIdInput = this.replyForm.querySelector( 'input[name="comment[parentId]"]' );
		parentIdInput.value = this.replyButton.dataset.commentId;

		const cancelButton = this.replyForm.querySelector( '#comment_cancel' );
		cancelButton.style.display = 'block';
		cancelButton.addEventListener( 'click', () => this.removeReplyForm() );

		this.replyButton.parentNode.appendChild( this.replyForm );
		this.replyButton.style.display = 'none';

		this.replyForm.querySelector( '#comment_authorName' ).focus();
	}

	removeReplyForm() {
		if ( this.replyForm ) {
			this.replyForm.remove();
			this.replyButton.style.display = 'block';
		}
	}
}