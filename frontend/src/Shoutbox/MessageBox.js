import React, {Component} from 'react';

class MessageBox extends Component {
    constructor(props) {
        super(props);
        this.state = {
            message: ''
        }
    }

    componentDidMount() {
        this.onChange = this.onChange.bind(this);
    }

    onChange() {
        return (e) => {
            this.setState({
                message: e.target.value
            })
        }
    }

    send() {
        return (e) => {
            e.preventDefault();
            this.props.onMessageSend(this.state.message);
            this.setState({message: ''});
        }
    }

    render() {
        return (
            <form onSubmit={this.send()}>
                <div className="field">
                    <label className="label">Message</label>
                    <div className="control">
                        <textarea className="textarea"
                                  datatype="text"
                                  name="message"
                                  value={this.state.message}
                                  onChange={this.onChange()}
                                  onKeyDown={this.onChange()}
                                  required></textarea>
                    </div>
                </div>
                <div className="field is-grouped">
                    <div className="control">
                        <button className="button is-link">Send</button>
                    </div>
                </div>
            </form>
        );
    }
}

export default MessageBox;
