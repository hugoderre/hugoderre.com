import { Controller } from 'stimulus';

export default class extends Controller {
	connect() {
		this.baseForm = this.element.querySelector( '.comments__form' );
		this.addFanthomFormSubmitListener( this.baseForm.querySelector( 'form[name=comment]' ) );
		this.replyForm = null;
		this.replyButton = null;
	}

	replyButtonClick( e ) {
		this.removeReplyForm(); // remove any existing reply form

		this.replyButton = e.target.parentNode;
		this.replyForm = this.baseForm.cloneNode( true );
		this.addFanthomFormSubmitListener( this.replyForm.querySelector( 'form[name=comment]' ) );

		const replyFormInputs = {
			authorName: this.replyForm.querySelector( '#comment_authorName' ),
			content: this.replyForm.querySelector( '#comment_content' )
		}

		// Update the form inputs to be unique
		for ( const [ key, input ] of Object.entries( replyFormInputs ) ) {
			input.value = '';
			input.parentNode.querySelector( 'label' ).setAttribute( 'for', input.id + '_reply' );
			input.id = input.id + '_reply';
		}

		const parentIdInput = this.replyForm.querySelector( 'input[name="comment[parentId]"]' );
		parentIdInput.value = this.replyButton.dataset.commentId;

		const cancelButton = this.replyForm.querySelector( '#comment_cancel' );
		cancelButton.style.display = 'block';
		cancelButton.addEventListener( 'click', () => this.removeReplyForm() );

		this.replyButton.parentNode.appendChild( this.replyForm );
		this.replyButton.style.display = 'none';

		replyFormInputs.authorName.focus();
	}

	removeReplyForm() {
		if ( this.replyForm ) {
			this.replyForm.remove();
			this.replyButton.style.display = 'flex';
		}
	}

	addFanthomFormSubmitListener( form ) {
		form.addEventListener( 'submit', ( e ) => {
			fathom.trackGoal( 'GLOMEBIK', 0 );
		} );
	}
}