<script type="text/babel">
  function SelectInput(props){
      const containerStyle={
        border: "1px solid black",
        width: "90%",
        padding: "10px",
        borderRadius: "20px",
        position: "relative",
        marginBottom:'30px',
        marginTop:'15px'
      }
      const labelStyle={
        position: "absolute",
        top: "-15px",
        background: "white",
        left: "10px"
      }
      const inputStyle={
        width: "95%",
        border: "none",
        outline: "none"
      }
      return (
        <div style={containerStyle}>
            <label style={labelStyle}>{props.label}</label>
            <select onChange={props.onChange} value={props.value} style={inputStyle} name={props.name} type={props.type}>
              {props.children}
            </select>
          </div>
      )
    }
</script>